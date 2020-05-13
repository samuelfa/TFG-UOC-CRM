<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Customer\CustomerRepository as CustomerRepositoryInterface;
use App\Domain\Customer\Customer;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository implements CustomerRepositoryInterface
{
    public function __construct(ManagerRegistry $em)
    {
        parent::__construct($em, Customer::class);
    }

    public function save(Customer $customer): void
    {
        $this->_em->persist($customer);
    }

    public function findOneByNif(NIF $nif): ?Customer
    {
        /** @var null|Customer $entity */
        $entity = $this->find((string) $nif);
        return $entity;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?Customer
    {
        /** @var null|Customer $entity */
        $entity = $this->findOneBy([
            'email' => (string) $emailAddress
        ]);
        return $entity;
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function remove(Customer $customer): void
    {
        $this->_em->remove($customer);
    }

    public function total(): int
    {
        return $this->count([]);
    }
}