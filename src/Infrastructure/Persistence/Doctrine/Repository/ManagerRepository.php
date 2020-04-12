<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Employee\ManagerRepository as ManagerRepositoryInterface;
use App\Domain\Employee\Manager;
use App\Domain\ValueObject\NIF;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ManagerRepository extends EntityRepository  implements ManagerRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Manager::class));
    }

    public function save(Manager $manager): void
    {
        $this->_em->persist($manager);
    }

    public function findOneByNif(NIF $nif): ?Manager
    {
        /** @var null|Manager $entity */
        $entity = $this->findOneBy([
            'nif' => $nif
        ]);

        return $entity;
    }
}