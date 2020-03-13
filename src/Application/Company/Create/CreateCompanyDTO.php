<?php

namespace App\Application\Company\Create;

final class CreateCompanyDTO
{
    private string $namespace;
    private string $name;
    private string $emailAddress;

    public function __construct(string $namespace, string $name, string $emailAddress)
    {
        $this->namespace    = $namespace;
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
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
}
