<?php

namespace App\Domain\Manager;

use App\Domain\ValueObject\NIF;

interface ManagerRepository
{
    public function findOneByNif(NIF $nif): ?Manager;
    public function save(Manager $manager): void;
}
