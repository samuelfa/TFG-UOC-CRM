<?php


namespace App\Application\Activity\Create;


use App\Application\DTO;

class CreateActivityDTO implements DTO
{
    private string $name;
    private \DateTimeImmutable $startAt;
    private \DateTimeImmutable $finishAt;
    private int $category;

    public function __construct(string $name, string $startAt, string $finishAt, int $category)
    {
        $this->name       = $name;
        $this->startAt    = new \DateTimeImmutable($startAt);
        $this->finishAt   = new \DateTimeImmutable($finishAt);
        $this->category = $category;
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

    public function categoryId(): int
    {
        return $this->category;
    }
}