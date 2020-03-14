<?php


namespace App\Infrastructure\Symfony\Event\Company;


use App\Domain\Company\CompanyEventDispatcher as CompanyEventDispatcherInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class CompanyEventDispatcher implements CompanyEventDispatcherInterface
{
    private EventDispatcherInterface $dispatcher ;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(string $namespace): void
    {
        $this->dispatcher->dispatch(new CompanyCreatedEvent($namespace));
    }
}