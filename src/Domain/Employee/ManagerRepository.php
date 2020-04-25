<?php

namespace App\Domain\Employee;

use App\Domain\Repository;
use App\Domain\ValueObject\NIF;

interface ManagerRepository extends Repository
{
    public function findOneByNif(NIF $nif): ?Manager;

    /**
     * @return Manager[]
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function findAll();
    public function save(Manager $manager): void;
}
