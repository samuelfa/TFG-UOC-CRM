<?php


namespace App\Infrastructure\Symfony\Event\Manager;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ManagerCreatedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ManagerCreatedEvent::class => 'onEvent'
        ];
    }

    public function onEvent(ManagerCreatedEvent $event): void
    {
        //Nothing to do... for now
    }
}