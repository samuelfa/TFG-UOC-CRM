<?php


namespace App\Application\Customer\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Customer\CustomerLinkedWithFamiliars;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Familiar\FamiliarRepository;

class CustomerDeleteService implements TransactionalService
{
    private CustomerRepository $repository;
    private FamiliarRepository $familiarRepository;

    public function __construct(CustomerRepository $repository, FamiliarRepository $familiarRepository)
    {
        $this->repository = $repository;
        $this->familiarRepository = $familiarRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteCustomerDTO $dto */
        $customer = $this->repository->findOneByNif($dto->nif());
        if(!$customer){
            throw new CustomerNotFound($dto->nif());
        }

        $familiars = $this->familiarRepository->findByCustomer($customer);
        if(!empty($familiars)){
            throw new CustomerLinkedWithFamiliars($dto->nif());
        }

        $this->repository->remove($customer);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteCustomerDTO::class;
    }
}