<?php


namespace App\Domain\Login;


use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\Password;

class ForgotPasswordEmail
{
    private EmailAddress $emailAddress;
    private string $token;
    private \DateTimeImmutable $createdAt;

    public function __construct(EmailAddress $emailAddress, string $token, \DateTimeImmutable $date)
    {
        $this->emailAddress = $emailAddress;
        $this->token = $token;
        $this->createdAt = $date;
    }

    public static function create(EmailAddress $emailAddress): self
    {
        return new self($emailAddress, self::password($emailAddress), new \DateTimeImmutable());
    }

    public function validate(string $hash): bool
    {
        $hash = base64_decode($hash);
        return password_verify($this->token, $hash);
    }

    public function hash(): string
    {
        return base64_encode($this->token);
    }

    public function isActive(): bool
    {
        $now = new \DateTime();
        return $this->expiresAt() > $now;
    }

    private static function password(EmailAddress $emailAddress): string
    {
        return Password::encode(sprintf('%s:%s', random_bytes(10), $emailAddress));
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function expiresAt(): \DateTimeImmutable
    {
        return $this->createdAt->add(new \DateInterval('P7D'));
    }

    public function regenerate(): void
    {
        $this->token = self::password($this->emailAddress);
        $this->createdAt = new \DateTimeImmutable();
    }
}