<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Events;

interface DomainEventsPublisher
{
    public function publish(array $events);
}
