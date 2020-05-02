<?php


namespace App\Application\Category\Edit;


use App\Application\DTO;

class EditCategoryDTO implements DTO
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->name = trim($name);
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}