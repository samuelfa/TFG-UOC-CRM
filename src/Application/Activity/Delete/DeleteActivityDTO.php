<?php


namespace App\Application\Activity\Delete;


use App\Application\DTO;

class DeleteActivityDTO implements DTO
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