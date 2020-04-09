<?php

namespace App\Domain\ValueObject;

class InvalidNifException extends \RuntimeException
{
    public function __construct($value)
    {
        parent::__construct("The NIF {$value} provided has an invalid format");
    }

}
