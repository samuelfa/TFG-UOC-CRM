<?php

namespace App\Domain\User;

use App\Domain\Person\AbstractPerson;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\URL;

class User extends AbstractPerson
{
    protected string $password;
    protected EmailAddress $email;
    protected string $role;

    public function __construct(
        string $nif,
        string $name,
        string $surname,
        \DateTimeInterface $birthday,
        URL $portrait,
        string $password,
        EmailAddress $email,
        int $role
    )
    {
        parent::__construct($nif, $name, $surname, $birthday, $portrait);
        $this->password = $password;
        $this->email    = $email;
        $this->role = $role;
    }

    public function password(): string
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
