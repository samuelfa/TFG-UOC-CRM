<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository as FamiliarRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class FamiliarRepository extends EntityRepository  implements FamiliarRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Familiar::class));
    }

    public function save(Familiar $company): void
    {
        $this->_em->persist($company);
    }
}