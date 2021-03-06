<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Employee\Manager;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ManagerRepository extends ServiceEntityRepository implements \App\Domain\Employee\ManagerRepository
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Manager::class);
    }

    public function save(Manager $worker): void
    {
        $this->_em->persist($worker);
    }

    public function findOneByNif(NIF $nif): ?Manager
    {
        /** @var null|Manager $entity */
        $entity = $this->find((string) $nif);
        return $entity;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Manager
    {
        /** @var null|Manager $entity */
        $entity = $this->findOneBy([
            'email' => (string) $emailAddress
        ]);
        return $entity;
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Manager $manager): void
    {
        $this->_em->remove($manager);
    }
}