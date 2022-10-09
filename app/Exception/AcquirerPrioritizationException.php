<?php

namespace App\Exception;

class AcquirerPrioritizationException extends HttpException
{
    public function __construct()
    {
        parent::__construct('An error occurred during the acquirer prioritization query.', 424);

        $this->status = 424;
        $this->response = [
            'message' => $this->getMessage(),
            'dependency' => 'acquiring-rules'
        ];
    }
}
