<?php


namespace App\Domain;


class EmailAddressNotFound extends \RuntimeException
{
    public function __construct(string $emailAddress)
    {
        parent::__construct("Email address {$emailAddress} not found");
    }

}