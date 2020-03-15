<?php

namespace App\Domain\Manager;

interface ManagerRepository
{
    public function findOneByNif(string $namespace): ?Manager;
    public function save(Manager $company): void;
}
