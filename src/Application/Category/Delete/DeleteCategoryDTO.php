<?php


namespace App\Application\Category\Delete;


use App\Application\DTO;

class DeleteCategoryDTO implements DTO
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function id(): int
    {
        return $this->id;
    }
}