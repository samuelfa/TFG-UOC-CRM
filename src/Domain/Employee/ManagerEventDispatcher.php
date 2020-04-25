<?php


namespace App\Domain\Employee;


use App\Application\Manager\Create\CreateManagerDTO;

interface ManagerEventDispatcher
{
    public function created(CreateManagerDTO $dto): void;
}