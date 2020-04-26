<?php /** @noinspection DuplicatedCode */


namespace App\Application\Worker\Create;


use App\Application\DTO;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Domain\ValueObject\URL;

class CreateWorkerDTO implements DTO
{
    private NIF $nif;
    private EmailAddress $emailAddress;
    private ?string $name;
    private ?string $surname;
    private ?\DateTime $birthday;
    private ?URL $portrait;
    private Password $password;

    public function __construct(
        string $nif,
        string $emailAddress,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password
    )
    {
        $this->nif          = new NIF($nif);
        $this->emailAddress = new EmailAddress($emailAddress);
        $this->name         = $name ?? null;
        $this->surname      = $surname ?? null;
        $this->birthday     = !empty($birthday) ? new \DateTime($birthday) : null;
        $this->portrait     = !empty($portrait) ? new URL($portrait) : null;
        $this->password     = Password::encode($password);
    }

    public function nif(): NIF
    {
        return $this->nif;
    }

    public function emailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function surname(): ?string
    {
        return $this->surname;
    }

    public function birthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function portrait(): ?URL
    {
        return $this->portrait;
    }

    public function password(): Password
    {
        return $this->password;
    }
}