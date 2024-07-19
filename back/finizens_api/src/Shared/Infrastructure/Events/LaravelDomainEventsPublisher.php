<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Events;

use App\Jobs\PublishDomainEvent;
use Src\Shared\Domain\Events\DomainEventsPublisher;

abstract class LaravelDomainEventsPublisher implements DomainEventsPublisher
{
    public function publish(array $events)
    {
        foreach ($events as $event) {
            PublishDomainEvent::dispatch($event);
        }
    }
}
