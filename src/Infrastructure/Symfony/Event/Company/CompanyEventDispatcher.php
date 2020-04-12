<?php


namespace App\Infrastructure\Symfony\Event\Company;


use App\Application\Company\Create\CreateCompanyDTO;
use App\Domain\Company\CompanyEventDispatcher as CompanyEventDispatcherInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class CompanyEventDispatcher implements CompanyEventDispatcherInterface
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(CreateCompanyDTO $dto): void
    {
        $this->dispatcher->dispatch(new CompanyCreatedEvent($dto));
    }
}