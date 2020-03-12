<?php

namespace App\Management\Company\Domain;

use App\Shared\Domain\ValueObject\Uuid;

interface CompanyRepository
{
    public function findOneById(Uuid $id): ?Company;
    public function save(Company $company): void;
}
