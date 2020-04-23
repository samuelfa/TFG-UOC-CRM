<?php

namespace App\Domain\Familiar;


interface FamiliarRepository
{
    /**
     * @return Familiar[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Familiar $company): void;
}
