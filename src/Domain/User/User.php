<?php

namespace App\Domain\User;

use App\Domain\Person\AbstractPerson;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\Role;
use App\Domain\ValueObject\URL;

abstract class User extends AbstractPerson
{
    protected string $password;
    protected EmailAddress $email;
    protected string $role;

    public function __construct(
        NIF $nif,
        Password $password,
        EmailAddress $email,
        Role $role,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    )
    {
        parent::__construct($nif, $name, $surname, $birthday, $portrait);
        $this->password = $password;
        $this->email    = $email;
        $this->role = $role;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function role(): int
    {
        return $this->role;
    }
}
