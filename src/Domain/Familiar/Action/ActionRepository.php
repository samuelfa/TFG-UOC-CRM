<?php


namespace App\Domain\Familiar\Action;


use App\Domain\Familiar\Familiar;
use App\Domain\Repository;

interface ActionRepository extends Repository
{
    /**
     * @return Action[]
     */
    public function findByFamiliar(Familiar $familiar): array;
    public function total(Familiar $familiar): int;
}