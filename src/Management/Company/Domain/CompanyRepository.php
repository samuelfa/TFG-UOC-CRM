<?php

namespace App\Management\Company\Domain;

interface CompanyRepository
{
    public function save(Company $company): void;
}
