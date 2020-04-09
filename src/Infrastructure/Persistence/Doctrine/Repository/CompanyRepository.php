<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository as CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class CompanyRepository implements CompanyRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOneByNamespace(string $namespace): ?Company
    {
        return $this->entityManager->find(Company::class, $namespace);
    }

    public function save(Company $company): void
    {
        $this->entityManager->persist($company);
    }
}