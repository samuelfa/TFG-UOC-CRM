<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository;

class InMemoryCompanyRepository implements CompanyRepository
{
    private array $list;

    public function findOneByNamespace(string $namespace): Company
    {
        return $this->list[$namespace] ?? null;
    }

    public function save(Company $company): void
    {
        $id = $company->namespace();
        $this->list[$id] = $company;
    }
}