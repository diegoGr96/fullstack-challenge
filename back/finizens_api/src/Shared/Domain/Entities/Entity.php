<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Entities;

abstract class Entity
{
    private array $domainEvents = [];

    protected function recordDomainEvent($event): void
    {
        $this->domainEvents[] = $event;
    }

    public function releaseDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
