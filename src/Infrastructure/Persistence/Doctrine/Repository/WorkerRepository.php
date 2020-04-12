<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\ValueObject\NIF;
use App\Domain\Worker\WorkerRepository as WorkerRepositoryInterface;
use App\Domain\Worker\Worker;
use Doctrine\ORM\EntityManagerInterface;

class WorkerRepository implements WorkerRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Worker $worker): void
    {
        $this->entityManager->persist($worker);
    }

    public function findOneByNif(NIF $nif): ?Worker
    {
        $repository = $this->entityManager->getRepository(Worker::class);
        return $repository->findOneBy([
            'nif' => $nif
        ]);
    }
}