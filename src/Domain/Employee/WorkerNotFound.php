<?php


namespace App\Domain\Employee;


class WorkerNotFound extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("Worker {$nif} not found");
    }

}