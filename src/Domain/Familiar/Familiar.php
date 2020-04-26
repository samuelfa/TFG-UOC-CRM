<?php

namespace App\Domain\Familiar;

use App\Domain\Person\AbstractPerson;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\URL;

class Familiar extends AbstractPerson
{
    public static function create(
        NIF $nif,
        ?string $name = null,
        ?string $surname = null,
        ?\DateTimeInterface $birthday = null,
        ?URL $portrait = null
    ): self
    {
        return new static(
            $nif,
            $name,
            $surname,
            $birthday,
            $portrait
        );
    }

    public function update(
        ?string $name,
        ?string $surname,
        ?\DateTime $birthday,
        ?URL $portrait
    ): void
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->birthday = $birthday;
        $this->portrait = $portrait;
    }
}
