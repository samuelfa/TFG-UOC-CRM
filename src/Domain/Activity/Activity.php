<?php

namespace App\Domain\Activity;


use App\Domain\Category\Category;

class Activity
{
    private ?int $id;
    private string $name;
    private Category $category;
    private \DateTimeImmutable $startAt;
    private \DateTimeImmutable $finishAt;

    public function __construct(?int $id, string $name, \DateTimeImmutable $startAt, \DateTimeImmutable $finishAt, Category $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->startAt = $startAt;
        $this->finishAt = $finishAt;
        $this->category = $category;
    }

    public static function create(string $name, \DateTimeImmutable $startAt, \DateTimeImmutable $finishAt, Category $category): self
    {
        return new self(
            null,
            $name,
            $startAt,
            $finishAt,
            $category
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function startAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }

    public function finishAt(): \DateTimeImmutable
    {
        return $this->finishAt;
    }

    public function update(string $name, \DateTimeImmutable $startAt, \DateTimeImmutable $finishAt, Category $category): void
    {
        $this->name = $name;
        $this->startAt = $startAt;
        $this->finishAt = $finishAt;
        $this->category = $category;
    }

    public function isStarted(): bool
    {
        return $this->startAt->getTimestamp() < time();
    }
}
