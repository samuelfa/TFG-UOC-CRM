<?php


namespace App\Management\Company\Infrastructure\Doctrine;


use App\Management\Company\Domain\Company;
use App\Management\Company\Domain\CompanyRepository;
use Doctrine\ORM\EntityManager;

class DoctrineCompanyRepository implements CompanyRepository
{
    private EntityManager $entityManager;

    public function save(Company $company): void
    {
        $this->entityManager->flush($company);
    }
}