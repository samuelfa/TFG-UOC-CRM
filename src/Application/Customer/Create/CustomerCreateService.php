<?php


namespace App\Application\Customer\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsNif;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;

class CustomerCreateService implements TransactionalService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var CreateCustomerDTO $dto */
        $nif = $dto->nif();

        if($this->repository->findOneByNif($nif)){
            throw new AlreadyExistsNif($nif);
        }

        $customer = Customer::create(
            $nif,
            $dto->emailAddress(),
            $dto->password(),
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait()
        );

        $this->repository->save($customer);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return CreateCustomerDTO::class;
    }
}