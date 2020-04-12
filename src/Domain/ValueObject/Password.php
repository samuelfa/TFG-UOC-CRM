<?php


namespace App\Domain\ValueObject;

class Password
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function encode(string $password): self
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return new self($hash);
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