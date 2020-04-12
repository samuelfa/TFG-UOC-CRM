<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Customer\CustomerRepository as CustomerRepositoryInterface;
use App\Domain\Customer\Customer;
use App\Domain\ValueObject\NIF;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository  implements CustomerRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Customer::class));
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