<?php

namespace App\Application\Company\Create;

use App\Application\DTO;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;

final class CreateCompanyDTO implements DTO
{
    private string $namespace;
    private string $name;
    private NIF $nif;
    private EmailAddress $emailAddress;
    private Password $password;

    public function __construct(
        string $namespace,
        string $name,
        string $nif,
        string $emailAddress,
        string $password
    )
    {
        $this->namespace    = $namespace;
        $this->name         = $name;
        $this->nif          = new NIF($nif);
        $this->emailAddress = new EmailAddress($emailAddress);
        $this->password     = Password::encode($password);
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function nif(): NIF
    {
        return $this->nif;
    }
}
