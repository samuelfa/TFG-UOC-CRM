<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository as CompanyRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CompanyRepository extends ServiceEntityRepository implements CompanyRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Company::class);
    }

    public function findOneByNamespace(string $namespace): ?Company
    {
        return $this->_em->find(Company::class, $namespace);
    }

    public function save(Company $company): void
    {
        $this->_em->persist($company);
    }
}