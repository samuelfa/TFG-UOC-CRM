<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Customer\CustomerRepository as CustomerRepositoryInterface;
use App\Domain\Customer\Customer;
use App\Domain\ValueObject\NIF;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends UserRepository implements CustomerRepositoryInterface
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
        $entity = $this->findOneBy([
            'nif' => $nif
        ]);

        return $entity;
    }
}