<?php


namespace App\Infrastructure\Persistence\Doctrine;


use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository;
use Doctrine\ORM\EntityManager;

class DoctrineCompanyRepository implements CompanyRepository
{
    private EntityManager $entityManager;

    public function findOneByNamespace(string $namespace): ?Company
    {
        return $this->entityManager->find(Company::class, $namespace);
    }

    public function save(Company $company): void
    {
        $this->entityManager->flush($company);
    }
}