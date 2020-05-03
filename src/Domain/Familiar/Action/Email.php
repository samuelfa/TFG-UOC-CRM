<?php


namespace App\Domain\Familiar\Action;


use App\Domain\Familiar\Familiar;
use App\Domain\ValueObject\EmailAddress;

class Email implements Action
{
    private ?int $id;
    private string $subject;
    private string $body;
    /**
     * @var EmailAddress[]
     */
    private array $recipients;
    private Familiar $familiar;
    private \DateTimeImmutable $createdAt;

    /**
     * @param EmailAddress[] $recipients
     */
    public function __construct(?int $id, string $subject, string $body, array $recipients, Familiar $familiar, \DateTimeImmutable $createdAt)
    {
        $this->id         = $id;
        $this->subject    = $subject;
        $this->body       = $body;
        $this->recipients = $recipients;
        $this->familiar   = $familiar;
        $this->createdAt  = $createdAt;
    }

    /**
     * @param EmailAddress[] $recipients
     * @return Email
     */
    public static function create(string $subject, string $body, array $recipients, Familiar $familiar): self
    {
        return new self(null, $subject, $body, $recipients, $familiar, new \DateTimeImmutable());
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return EmailAddress[]
     */
    public function recipients(): array
    {
        return $this->recipients;
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