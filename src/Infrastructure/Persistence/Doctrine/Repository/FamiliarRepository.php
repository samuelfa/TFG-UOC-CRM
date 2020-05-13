<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository as FamiliarRepositoryInterface;
use App\Domain\ValueObject\NIF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FamiliarRepository extends ServiceEntityRepository implements FamiliarRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Familiar::class);
    }

    public function save(Familiar $familiar): void
    {
        $this->_em->persist($familiar);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Familiar $familiar): void
    {
        $this->_em->remove($familiar);
    }

    public function findOneByNif(NIF $nif): ?Familiar
    {
        /** @var null|Familiar $entity */
        $entity = $this->find((string) $nif);
        return $entity;
    }

    public function findByCustomer(Customer $customer): array
    {
        return $this->findBy([
           'customer' => $customer
        ]);
    }

    public function total(): int
    {
        return $this->count([]);
    }
}