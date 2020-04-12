<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository as CompanyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository  implements CompanyRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Company::class));
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