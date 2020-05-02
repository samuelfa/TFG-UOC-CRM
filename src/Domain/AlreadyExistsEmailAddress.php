<?php


namespace App\Domain;


use App\Domain\ValueObject\EmailAddress;

class AlreadyExistsEmailAddress extends \RuntimeException
{
    public function __construct(EmailAddress $emailAddress)
    {
        parent::__construct(sprintf('The email address %s is already in use', $emailAddress));
    }

}