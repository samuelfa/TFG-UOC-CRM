<?php

namespace App\Domain\Company;

interface CompanyRepository
{
    public function findOneByNamespace(string $namespace): ?Company;
    public function save(Company $company): void;
}
