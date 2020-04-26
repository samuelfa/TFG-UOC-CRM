<?php

namespace App\Domain\User;

use App\Domain\Person\AbstractPerson;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\URL;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class User extends AbstractPerson implements UserInterface
{
    protected Password $password;
    protected EmailAddress $email;

    public function __construct(
        NIF $nif,
        Password $password,
        EmailAddress $email,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    )
    {
        parent::__construct($nif, $name, $surname, $birthday, $portrait);
        $this->password = $password;
        $this->email    = $email;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function update(
        EmailAddress $emailAddress,
        ?string $name,
        ?string $surname,
        ?\DateTime $birthday,
        ?URL $portrait
    ): void
    {
        $this->email = $emailAddress;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthday = $birthday;
        $this->portrait = $portrait;
    }
}
