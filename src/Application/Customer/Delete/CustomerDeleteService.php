<?php


namespace App\Application\Customer\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Customer\CustomerRepository;

class CustomerDeleteService implements TransactionalService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteCustomerDTO $dto */
        $customer = $this->repository->findOneByNif($dto->nif());
        if(!$customer){
            throw new CustomerNotFound($dto->nif());
        }

        $this->repository->remove($customer);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteCustomerDTO::class;
    }
}