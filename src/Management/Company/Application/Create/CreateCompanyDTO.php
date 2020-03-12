<?php

namespace App\Management\Company\Application\Create;

final class CreateCompanyDTO
{
    private string $uuid;
    private string $name;
    private string $emailAddress;

    public function __construct(string $name, string $emailAddress, string $uuid = '')
    {
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
        $this->uuid         = $uuid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function emailAddress(): string
    {
        return $this->emailAddress;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function createFromUuid(string $uuid): self
    {
        return new CreateCompanyDTO($this->name, $this->emailAddress, $uuid);
    }
}
