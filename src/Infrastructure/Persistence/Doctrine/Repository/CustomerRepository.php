<?php


namespace App\Infrastructure\Persistence\Doctrine\Repository;


use App\Domain\Customer\CustomerRepository as CustomerRepositoryInterface;
use App\Domain\Customer\Customer;
use App\Domain\ValueObject\NIF;
use Doctrine\ORM\EntityManagerInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Customer $customer): void
    {
        $this->entityManager->persist($customer);
    }

    public function findOneByNif(NIF $nif): ?Customer
    {
        $repository = $this->entityManager->getRepository(Customer::class);
        return $repository->findOneBy([
            'nif' => $nif
        ]);
    }
}