<?php


namespace App\Domain\Customer;


class CustomerNotFound extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("Customer {$nif} not found");
    }

}