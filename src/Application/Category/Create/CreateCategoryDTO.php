<?php


namespace App\Application\Category\Create;


use App\Application\DTO;

class CreateCategoryDTO implements DTO
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = trim($name);
    }

    public function name(): string
    {
        return $this->name;
    }
}