<?php

namespace App\Service\AcquirerPrioritization;

class AcquirerPrioritization
{
    private string $acquirer;

    private int $priority;

    private ?self $alternative;

    public static function fromArray(array $prioritization): self
    {
        $current = array_shift($prioritization);

        $self = new self();
        $self->acquirer = $current['acquirer'];
        $self->priority = $current['priority'];
        $self->alternative = (count($prioritization)) ? static::fromArray($prioritization) : null;

        return $self;
    }

    public function getAcquirer(): string
    {
        return $this->acquirer;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getAlternative(): ?AcquirerPrioritization
    {
        return $this->alternative;
    }

    public function hasAlternative(): bool
    {
        return $this->alternative instanceof static;
    }

    public function toArray(): array
    {
        $array = [];

        if ($this->hasAlternative()) {
            $array = $this->alternative->toArray();
        }

        array_unshift($array, [
            'acquirer' => $this->acquirer,
            'priority' => $this->priority
        ]);

        return $array;
    }
}
