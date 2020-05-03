<?php


namespace App\Domain\Familiar\Action;


use App\Domain\Familiar\Familiar;

class Note implements Action
{
    private ?int $id;
    private string $message;
    private bool $private;
    private Familiar $familiar;
    private \DateTimeImmutable $createdAt;

    public function __construct(?int $id, string $message, bool $private, Familiar $familiar, \DateTimeImmutable $createdAt)
    {
        $this->id        = $id;
        $this->message   = $message;
        $this->private   = $private;
        $this->familiar  = $familiar;
        $this->createdAt = $createdAt;
    }

    public static function create(string $message, bool $private, Familiar $familiar): self
    {
        return new self(null, $message, $private, $familiar, new \DateTimeImmutable());
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function familiar(): Familiar
    {
        return $this->familiar;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}