<?php

namespace App\Management\Company\Application\Create;

use App\Shared\Domain\Bus\Command\Command;

final class CreateCompanyCommand implements Command
{
    private string $name;
    private string $emailAddress;

    public function __construct(string $name, string $emailAddress)
    {
        $this->name     = $name;
        $this->emailAddress = $emailAddress;
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
