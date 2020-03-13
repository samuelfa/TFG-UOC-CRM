<?php


namespace App\Infrastructure\Symfony\Subscriber;

use App\Domain\Company\CloneCustomerRepository;
use App\Infrastructure\Symfony\Event\CompanyCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CompanyCreatedSubscriber implements EventSubscriberInterface
{
    private CloneCustomerRepository $repository;

    public function __construct(CloneCustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CompanyCreatedEvent::NAME => 'onEvent'
        ];
    }

    public function onEvent(CompanyCreatedEvent $event): void
    {
        $namespace = $event->namespace();
        $this->repository->create($namespace);
    }
}