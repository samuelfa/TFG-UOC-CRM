<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\ValueObject\NIF;
use App\Domain\Employee\WorkerRepository as WorkerRepositoryInterface;
use App\Domain\Employee\Worker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class WorkerRepository extends EntityRepository implements WorkerRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Worker::class));
    }

    public function save(Worker $worker): void
    {
        $this->_em->persist($worker);
    }

    public function findOneByNif(NIF $nif): ?Worker
    {
        /** @var null|Worker $entity */
        $entity = $this->findOneBy([
            'nif' => $nif
        ]);

        return $entity;
    }
}