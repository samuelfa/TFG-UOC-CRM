<?php

namespace App\Domain\Company;

use App\Domain\ValueObject\EmailAddress;

final class Company
{
    private string $namespace;
    private string $name;
    private EmailAddress $email;

    public function __construct(string $namespace, string $name, EmailAddress $email)
    {
        $this->namespace = $namespace;
        $this->name      = $name;
        $this->email = $email;
    }

    public static function create(string $namespace, string $name, EmailAddress $emailAddress): self
    {
        return new static($namespace, $name, $emailAddress);
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
        return $this->email;
    }
}
