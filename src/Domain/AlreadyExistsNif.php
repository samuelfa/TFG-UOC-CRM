<?php


namespace App\Domain;


use App\Domain\ValueObject\NIF;

class AlreadyExistsNif extends \RuntimeException
{
    public function __construct(NIF $nif)
    {
        parent::__construct(sprintf('The nif %s is already in use', $nif));
    }

}