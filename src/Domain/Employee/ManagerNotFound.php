<?php


namespace App\Domain\Employee;


class ManagerNotFound extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("Manager {$nif} not found");
    }

}