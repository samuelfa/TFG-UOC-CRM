<?php

namespace App\Domain\ValueObject;

class InvalidEmailAddressException extends \RuntimeException
{
    public function __construct($value)
    {
        parent::__construct("The email address {$value} provided has an invalid format");
    }

}
