<?php

namespace App\Domain\Employee;

use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\URL;

class Manager extends Employee
{
    public static function create(
        NIF $nif,
        EmailAddress $emailAddress,
        Password $password,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    ): self
    {
        return new self(
            $nif,
            $password,
            $emailAddress,
            $name,
            $surname,
            $birthday,
            $portrait
        );
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
