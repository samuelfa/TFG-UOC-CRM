<?php

namespace App\Domain\Employee;

use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;

class Manager extends Employee
{
    public static function create(NIF $nif, EmailAddress $emailAddress, Password $password): self
    {
        return new self($nif, $password, $emailAddress);
    }

    public function getRoles(): array
    {
        return [
            'ROLE_MANAGER',
            'ROLE_WORKER',
            'ROLE_USER'
        ];
    }
}
