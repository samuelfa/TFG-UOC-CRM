<?php


namespace App\Domain\Person;


use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\URL;

class AbstractPerson
{
    protected NIF $nif;
    protected ?string $name;
    protected ?string $surname;
    protected ?\DateTimeInterface $birthday;
    protected ?URL $portrait;

    public function __construct(
        NIF $nif,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    )
    {
        $this->nif = $nif;
        $this->name     = $name;
        $this->surname  = $surname;
        $this->birthday     = $birthday;
        $this->portrait = $portrait;
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

    public function birthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function portrait(): ?URL
    {
        return $this->portrait;
    }


}