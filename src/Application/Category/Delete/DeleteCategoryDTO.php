<?php


namespace App\Application\Category\Delete;


use App\Application\DTO;

class DeleteCategoryDTO implements DTO
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}