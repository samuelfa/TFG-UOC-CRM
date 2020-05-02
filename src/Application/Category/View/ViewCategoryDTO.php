<?php


namespace App\Application\Category\View;


use App\Application\DTO;

class ViewCategoryDTO implements DTO
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