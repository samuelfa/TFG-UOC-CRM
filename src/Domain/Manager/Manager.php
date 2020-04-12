<?php

namespace App\Domain\Manager;

use App\Domain\User\User;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;

class Manager extends User
{

    public static function create(NIF $nif, EmailAddress $emailAddress, Password $password): self
    {
        return new self($nif, $password, $emailAddress, RoleManager::create());
    }
}
