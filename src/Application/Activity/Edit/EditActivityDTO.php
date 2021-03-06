<?php


namespace App\Application\Activity\Edit;


use App\Application\DTO;

class EditActivityDTO implements DTO
{
    private int $id;
    private string $name;
    private \DateTimeImmutable $startAt;
    private \DateTimeImmutable $finishAt;
    private int $category;

    public function __construct(int $id, string $name, string $startAt, string $finishAt, int $category)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->startAt  = new \DateTimeImmutable($startAt);
        $this->finishAt = new \DateTimeImmutable($finishAt);
        $this->category = $category;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function startAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }

    public function finishAt(): \DateTimeImmutable
    {
        return $this->finishAt;
    }

    public function category(): int
    {
        return $this->category;
    }
}