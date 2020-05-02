<?php


namespace App\Application\Activity\Create;


use App\Application\DTO;

class CreateActivityDTO implements DTO
{
    private string $name;
    private \DateTime $startAt;
    private \DateTime $finishAt;
    private int $category;

    public function __construct(string $name, string $startAt, string $finishAt, int $category)
    {
        $this->name       = $name;
        $this->startAt    = new \DateTime($startAt);
        $this->finishAt   = new \DateTime($finishAt);
        $this->category = $category;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function startAt(): \DateTime
    {
        return $this->startAt;
    }

    public function finishAt(): \DateTime
    {
        return $this->finishAt;
    }

    public function categoryId(): int
    {
        return $this->category;
    }
}