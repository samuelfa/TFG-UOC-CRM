<?php


namespace App\Infrastructure\Symfony\Event\Manager;


use App\Application\Manager\Create\CreateManagerDTO;
use App\Domain\Employee\ManagerEventDispatcher as ManagerEventDispatcherInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class ManagerEventDispatcher implements ManagerEventDispatcherInterface
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(CreateManagerDTO $dto): void
    {
        $this->dispatcher->dispatch(new ManagerCreatedEvent($dto));
    }
}