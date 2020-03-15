<?php

namespace App\Domain\ValueObject;

class InvalidURLException extends \RuntimeException
{
    public function __construct($value)
    {
        parent::__construct("The URL {$value} provided has an invalid format");
    }

}
