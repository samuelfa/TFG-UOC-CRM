<?php


namespace App\Application\Customer\View;


use App\Application\DTO;
use App\Domain\ValueObject\NIF;

class ViewCustomerDTO implements DTO
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