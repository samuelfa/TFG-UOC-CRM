<?php

namespace App\Domain\Company;

use App\Domain\Repository;

interface CompanyRepository extends Repository
{
    public function findOneByNamespace(string $namespace): ?Company;
    public function save(Company $company): void;
}
