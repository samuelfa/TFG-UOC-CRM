<?php


namespace App\Management\Company\Infrastructure\Persistence\InMemory;


use App\Management\Company\Domain\Company;
use App\Management\Company\Domain\CompanyRepository;
use App\Shared\Domain\ValueObject\Uuid;

class InMemoryCompanyRepository implements CompanyRepository
{
    private array $list;

    public function findOneById(Uuid $id): Company
    {
        return $this->list[$id->value()] ?? null;
    }

    public function save(Company $company): void
    {
        $id = (string) $company->id();
        $this->list[$id] = $company;
    }
}