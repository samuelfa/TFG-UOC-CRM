<?php /** @noinspection DuplicatedCode */


namespace App\Application\Familiar\Create;


use App\Application\DTO;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\URL;

class CreateFamiliarDTO implements DTO
{
    private NIF $nif;
    private ?string $name;
    private ?string $surname;
    private ?\DateTime $birthday;
    private ?URL $portrait;
    private NIF $customer;

    public function __construct(
        string $nif,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $customer
    )
    {
        $this->nif          = new NIF($nif);
        $this->name         = $name ?? null;
        $this->surname      = $surname ?? null;
        $this->birthday     = !empty($birthday) ? new \DateTime($birthday) : null;
        $this->portrait     = !empty($portrait) ? new URL($portrait) : null;
        $this->customer     = new NIF($customer);
    }

    public function nif(): NIF
    {
        return $this->nif;
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

    public function customer(): NIF
    {
        return $this->customer;
    }
}