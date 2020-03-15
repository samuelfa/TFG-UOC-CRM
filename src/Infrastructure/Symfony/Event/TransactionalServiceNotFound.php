<?php


namespace App\Infrastructure\Symfony\Event;

class TransactionalServiceNotFound extends \RuntimeException
{
    public function __construct(string $dtoName)
    {
        parent::__construct(sprintf('Any service is subscriber with the DTO %s', $dtoName));
    }
}