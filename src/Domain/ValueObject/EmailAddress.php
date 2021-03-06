<?php

namespace App\Domain\ValueObject;

class EmailAddress
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailAddressException($value);
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function equals(self $emailAddress): bool
    {
        return $this->value() === $emailAddress->value();
    }
}
