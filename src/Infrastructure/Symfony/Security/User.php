<?php

namespace App\Infrastructure\Symfony\Security;

use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\Role;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private EmailAddress $email;
    private Password $password;
    private Role $role;

    public function __construct(
        EmailAddress $email,
        Password $password,
        Role $role
    )
    {
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
    }

    public static function createFromUser(\App\Domain\User\User $user): self
    {
        return new self($user->emailAddress(), $user->password(), $user->role());
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return [
            $this->role,
            // guarantee every user at least has ROLE_USER
            'ROLE_USER'
        ];
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
}
