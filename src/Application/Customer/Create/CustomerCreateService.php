<?php


namespace App\Application\Customer\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsEmailAddress;
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
        $emailAddress = $dto->emailAddress();

        if($this->repository->findOneByNif($nif)){
            throw new AlreadyExistsNif($nif);
        }

        if($this->repository->findOneByEmailAddress($emailAddress)){
            throw new AlreadyExistsEmailAddress($emailAddress);
        }

        $customer = Customer::create(
            $nif,
            $emailAddress,
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