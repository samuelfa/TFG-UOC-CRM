<?php


namespace App\Domain\ValueObject;


class URL
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidURLException($value);
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
}