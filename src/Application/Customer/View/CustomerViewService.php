<?php


namespace App\Application\Customer\View;


use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Customer\CustomerRepository;

class CustomerViewService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewCustomerDTO $dto): Customer
    {
        $customer = $this->repository->findOneByNif($dto->nif());
        if(!$customer){
            throw new CustomerNotFound($dto->nif());
        }

        return $customer;
    }
}