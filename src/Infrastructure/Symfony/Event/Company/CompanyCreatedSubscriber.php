<?php


namespace App\Infrastructure\Symfony\Event\Company;

use App\Domain\Company\CloneCustomerRepository;
use App\Domain\Manager\Manager;
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
            CompanyCreatedEvent::class => 'onEvent'
        ];
    }

    public function onEvent(CompanyCreatedEvent $event): void
    {
        $dto = $event->dto();

        $namespace    = $dto->namespace();
        $nif          = $dto->nif();
        $emailAddress = $dto->emailAddress();
        $password     = $dto->password();

        $manager = Manager::create($nif, $emailAddress, $password);

        $this->repository->create($namespace, $manager);
    }
}