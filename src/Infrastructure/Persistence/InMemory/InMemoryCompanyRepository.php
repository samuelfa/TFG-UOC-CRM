<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository;

class InMemoryCompanyRepository implements CompanyRepository
{
    private array $list;

    /**
     * @param Company[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $company){
            $this->list[$company->namespace()] = $company;
        }
    }

    public function findOneByNamespace(string $namespace): ?Company
    {
        return $this->list[$namespace] ?? null;
    }

    public function save(Company $company): void
    {
        $id = $company->namespace();
        $this->list[$id] = $company;
    }
}