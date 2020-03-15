<?php

namespace App\Application\Company\Create;

use App\Application\DTO;

final class CreateCompanyDTO implements DTO
{
    private string $namespace;
    private string $name;
    private string $emailAddress;
    private string $password;

    public function __construct(string $namespace, string $name, string $emailAddress, string $password)
    {
        $this->namespace    = $namespace;
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function password(): string
    {
        return $this->password;
    }
}
