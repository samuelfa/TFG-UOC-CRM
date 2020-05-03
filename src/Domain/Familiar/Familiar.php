<?php

namespace App\Domain\Familiar;

use App\Domain\Customer\Customer;
use App\Domain\Person\AbstractPerson;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\URL;

class Familiar extends AbstractPerson
{
    private ?Customer $customer;

    public function __construct(
        NIF $nif,
        Customer $customer,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    )
    {
        parent::__construct($nif, $name, $surname, $birthday, $portrait);
        $this->customer = $customer;
    }

    public static function create(
        NIF $nif,
        Customer $customer,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    ): self
    {
        return new static(
            $nif,
            $customer,
            $name,
            $surname,
            $birthday,
            $portrait
        );
    }

    public function update(
        ?string $name,
        ?string $surname,
        ?\DateTime $birthday,
        ?URL $portrait,
        Customer $customer
    ): void
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->birthday = $birthday;
        $this->portrait = $portrait;
        $this->customer = $customer;
    }

    public function customer(): ?Customer
    {
        return $this->customer;
    }
}
