<?php

namespace App\Management\Company\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\ValueObject\EmailAddress;
use App\Shared\Domain\ValueObject\Uuid;

final class Company extends AggregateRoot
{
    private Uuid $id;
    private string $name;
    private EmailAddress $email;

    public function __construct(Uuid $id, string $name, EmailAddress $email)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->email = $email;
    }

    public static function create(Uuid $id, string $name, EmailAddress $emailAddress): self
    {
        return new static($id, $name, $emailAddress);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }
}
