<?php


namespace App\Domain\Customer;


class CustomerLinkedWithFamiliars extends \RuntimeException
{
    public function __construct(string $nif)
    {
        parent::__construct("Customer {$nif} linked with familiars");
    }
}