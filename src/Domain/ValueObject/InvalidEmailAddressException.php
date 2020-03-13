<?php

namespace App\Domain\ValueObject;

class InvalidEmailAddressException extends \RuntimeException
{
    public function __construct($emailAddress)
    {
        parent::__construct("The email address {$emailAddress} provided has an invalid format");
    }

}
