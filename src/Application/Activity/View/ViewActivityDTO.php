<?php


namespace App\Application\Activity\View;


use App\Application\DTO;

class ViewActivityDTO implements DTO
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