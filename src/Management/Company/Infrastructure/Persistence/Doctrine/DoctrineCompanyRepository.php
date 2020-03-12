<?php


namespace App\Management\Company\Infrastructure\Persistence\Doctrine;


use App\Management\Company\Domain\Company;
use App\Management\Company\Domain\CompanyRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\EntityManager;

class DoctrineCompanyRepository implements CompanyRepository
{
    private EntityManager $entityManager;

    public function findOneById(Uuid $id): ?Company
    {
        return $this->entityManager->find(Company::class, $id);
    }

    public function save(Company $company): void
    {
        $this->entityManager->flush($company);
    }
}