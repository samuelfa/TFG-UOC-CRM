<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Manager\ManagerRepository as ManagerRepositoryInterface;
use App\Domain\Manager\Manager;
use App\Domain\ValueObject\NIF;
use Doctrine\ORM\EntityManagerInterface;

class ManagerRepository implements ManagerRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Manager $manager): void
    {
        $this->entityManager->persist($manager);
    }

    public function findOneByNif(NIF $nif): ?Manager
    {
        $repository = $this->entityManager->getRepository(Manager::class);
        return $repository->findOneBy([
            'nif' => $nif
        ]);
    }
}