<?php


namespace App\Application\Familiar\Delete;


use App\Application\DTO;
use App\Domain\ValueObject\NIF;

class DeleteFamiliarDTO implements DTO
{
    private NIF $nif;

    public function __construct(string $nif)
    {
        $this->nif = new NIF($nif);
    }

    public function nif(): NIF
    {
        return $this->nif;
    }
}