<?php

namespace App\Domain\Familiar;


use App\Domain\Repository;

interface FamiliarRepository extends Repository
{
    /**
     * @return Familiar[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Familiar $company): void;
}
