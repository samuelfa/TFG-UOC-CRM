<?php


namespace App\Application\Customer\DisplayList;


use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;

class CustomerListService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Customer[]
     */
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}