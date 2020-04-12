<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\ValueObject\NIF;
use App\Domain\Employee\WorkerRepository as WorkerRepositoryInterface;
use App\Domain\Employee\Worker;
use Doctrine\Persistence\ManagerRegistry;

class WorkerRepository extends UserRepository implements WorkerRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Worker::class);
    }

    public function save(Worker $worker): void
    {
        $this->_em->persist($worker);
    }

    public function findOneByNif(NIF $nif): ?Worker
    {
        /** @var null|Worker $entity */
        $entity = $this->findOneBy([
            'nif' => (string) $nif
        ]);

        return $entity;
    }
}