<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\Employee\WorkerRepository as WorkerRepositoryInterface;
use App\Domain\Employee\Worker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WorkerRepository extends ServiceEntityRepository implements WorkerRepositoryInterface
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
        $entity = $this->find((string) $nif);
        return $entity;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Worker
    {
        /** @var null|Worker $entity */
        $entity = $this->findOneBy([
            'email' => (string) $emailAddress
        ]);
        return $entity;
    }
}