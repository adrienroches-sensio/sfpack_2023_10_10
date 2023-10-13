<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MovieSubscriber implements EventSubscriberInterface
{
    public function notifyAllAdmins(MovieAddedEvent $event): void
    {
        dump('TODO : Fetch and notify all admins', $event);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieAddedEvent::class => [
                ['notifyAllAdmins', 0]
            ],
        ];
    }
}
