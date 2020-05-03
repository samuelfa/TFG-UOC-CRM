<?php


namespace App\Application\Login\Employee;


use App\Application\DTO;
use App\Domain\ValueObject\EmailAddress;

class ForgotPasswordDTO implements DTO
{
    private EmailAddress $emailAddress;

    public function __construct(string $value)
    {
        $this->emailAddress = new EmailAddress($value);
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }
}