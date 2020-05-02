<?php


namespace App\Domain\ValueObject;

use NifValidator\NifValidator;

class NIF
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        if (!NifValidator::isValid($value)) {
            throw new InvalidNifException($value);
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

    public function equals(self $nif): bool
    {
        return $this->value() === $nif->value();
    }
}