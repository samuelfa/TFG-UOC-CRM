<?php

namespace App\Domain\Category;


class Category
{
    private ?int $id;
    private string $name;

    public function __construct(?int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function create(string $name): self
    {
        return new self(null, $name);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function update(string $name): void
    {
        $this->name = $name;
    }
}
