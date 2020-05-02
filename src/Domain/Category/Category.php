<?php

namespace App\Domain\Category;


class Category
{
    private int $id;
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function id(): int
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
