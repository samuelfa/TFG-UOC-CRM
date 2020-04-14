<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository as FamiliarRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FamiliarRepository extends ServiceEntityRepository implements FamiliarRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Familiar::class);
    }

    public function save(Familiar $company): void
    {
        $this->_em->persist($company);
    }
}