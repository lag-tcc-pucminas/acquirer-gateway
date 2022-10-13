<?php

namespace App\Service;

use App\Acquirer\AcquirerFactory;
use App\Acquirer\Dto\AcquirerResult;
use App\Acquirer\Dto\AuthorizeResultData;
use App\Dto\PaymentContext;
use App\Enum\PaymentAttemptStatusEnum;
use App\Enum\PaymentStatusEnum;
use App\Exception\IdempotencyException;
use App\Facade\CircuitBreakerFacade;
use App\Http\Request\AuthorizeRequest;
use App\Model\Payment;
use App\Model\PaymentAcquirerAttempt;
use App\Repository\PaymentRepository;
use Hyperf\Utils\Str;

class PaymentService
{
    public function __construct(
        private AcquirerPrioritizationService $acquirerPrioritizationService,
        private CircuitBreakerFacade          $circuitBreakerService,
        private AcquirerFactory               $acquirerFactory,
        private PaymentRepository             $repository
    ) {
    }

    public function createPayment(AuthorizeRequest $request): Payment
    {
        $context = $this->buildContext($request);
        $payment = $this->buildPayment($request);

        $acquirerPrioritization = $this->acquirerPrioritizationService->getPrioritization(
            $context->card->brand,
            $payment->installments
        );

        $hasSuccessfulAttempt = false;

        while (!$hasSuccessfulAttempt && $acquirerPrioritization) {
            $payment->acquirer = $acquirerPrioritization->getAcquirer();

            if (
                !$this->circuitBreakerService->isAvailable($acquirerPrioritization->getAcquirer())
                && $acquirerPrioritization->hasAlternative()
            ) {
                $this->storeSkippedAttempt($payment);
                $acquirerPrioritization = $acquirerPrioritization->getAlternative();
                continue;
            }

            $this->authorizePayment($payment, $context);

            if ($payment->getCurrentAttempt()->status == PaymentAttemptStatusEnum::SUCCEEDED) {
                $hasSuccessfulAttempt = true;
            }

            $acquirerPrioritization = $acquirerPrioritization->getAlternative();
        }

        return $this->repository->save($payment);
    }

    private function buildContext(AuthorizeRequest $request): PaymentContext
    {
        return new PaymentContext($request->all());
    }

    private function buildPayment(AuthorizeRequest $request): Payment
    {
        $existentPayment = $this->repository->getByIdempotencyId($request->input('idempotency_id'));

        if ($existentPayment) {
            throw IdempotencyException::fromExistentPayment($existentPayment);
        }

        return new Payment([
            'idempotency_id' => $request->input('idempotency_id'),
            'status' => PaymentStatusEnum::INITIAL,
            'installments' => $request->input('installments'),
            'mode' => $request->input('mode'),
            'mcc' => $request->input('seller.mcc'),
            'value' => $request->input('value')
        ]);
    }

    private function authorizePayment(Payment $payment, PaymentContext $context): void
    {
        $this->initializeAttempt($payment);

        $acquirer = $this->acquirerFactory->get($payment->acquirer);
        $result = $acquirer->authorize($payment, $context);

        $this->handleAcquirerResult($payment, $result);
    }

    private function initializeAttempt(Payment $payment): void
    {
        $payment->acquirerAttempts->add(PaymentAcquirerAttempt::make([
            'acquirer' => $payment->acquirer,
            'status' => PaymentAttemptStatusEnum::PENDING,
            'external_reference' => Str::random(),
            'payment_id' => $payment->id
        ]));
    }

    private function storeSkippedAttempt(Payment $payment): void
    {
        $payment->acquirerAttempts->add(PaymentAcquirerAttempt::make([
            'acquirer' => $payment->acquirer,
            'status' => PaymentAttemptStatusEnum::SKIPPED,
            'external_reference' => Str::random(),
            'payment_id' => $payment->id
        ]));
    }

    private function handleAcquirerResult(Payment $payment, AcquirerResult $acquirerResult): void
    {
        if (!$acquirerResult->succeeded) {
            $payment->status = PaymentStatusEnum::FAILED;

            $attempt = $payment->getCurrentAttempt();
            $attempt->status = PaymentAttemptStatusEnum::FAILED;

            $this->circuitBreakerService->failure($payment->acquirer);
            return;
        }

        /** @var AuthorizeResultData $authorizeResultData */
        $authorizeResultData = $acquirerResult->data;

        $payment->status = $authorizeResultData->authorized
            ? PaymentStatusEnum::AUTHORIZED
            : PaymentStatusEnum::NOT_AUTHORIZED;

        $payment->acquirer_reference = $authorizeResultData->acquirerReference;
        $payment->nsu = $authorizeResultData->nsu;
        $payment->authorization_code = $authorizeResultData->authorizationCode;

        $attempt = $payment->getCurrentAttempt();
        $attempt->status = PaymentAttemptStatusEnum::SUCCEEDED;
        $attempt->acquirer_code = $authorizeResultData->code;
        $attempt->acquirer_message = $authorizeResultData->message;

        $this->circuitBreakerService->success($payment->acquirer);
    }
}
