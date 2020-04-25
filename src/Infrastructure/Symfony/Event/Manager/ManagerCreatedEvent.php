<?php


namespace App\Infrastructure\Symfony\Event\Manager;

use App\Application\Manager\Create\CreateManagerDTO;
use App\Domain\Event\Event as EventDomain;
use Symfony\Contracts\EventDispatcher\Event;

class ManagerCreatedEvent extends Event implements EventDomain
{
    private CreateManagerDTO $dto;

    public function __construct(CreateManagerDTO $dto)
    {
        $this->dto = $dto;
    }

    public function dto(): CreateManagerDTO
    {
        return $this->dto;
    }
}