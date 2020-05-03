<?php


namespace App\Application\Familiar\Action\SendEmail;


use App\Application\DTO;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;

class SendEmailDTO implements DTO
{
    private NIF $nif;
    private string $subject;
    private string $body;
    /**
     * @var EmailAddress[]
     */
    private array $recipients;

    public function __construct(string $nif, string $subject, string $body, array $recipients)
    {
        $this->nif       = new NIF($nif);
        $this->subject   = $subject;
        $this->body      = $body;

        $list = [];
        foreach ($recipients as $recipient){
            $list[] = new EmailAddress($recipient);
        }
        $this->recipients = $list;
    }

    public function nif(): NIF
    {
        return $this->nif;
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
}