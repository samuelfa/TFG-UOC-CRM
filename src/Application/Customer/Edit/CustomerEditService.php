<?php


namespace App\Application\Customer\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Customer\CustomerRepository;

class CustomerEditService implements TransactionalService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditCustomerDTO $dto */
        $nif = $dto->nif();
        $emailAddress = $dto->emailAddress();

        $customer = $this->repository->findOneByNif($nif);
        if(!$customer){
            throw new CustomerNotFound($nif);
        }

        $currentEmailAddress = $customer->emailAddress();
        if(!$currentEmailAddress->equals($emailAddress)
           && $this->repository->findOneByEmailAddress($emailAddress)){
            throw new AlreadyExistsEmailAddress($emailAddress);
        }

        $customer->update(
            $emailAddress,
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait(),
            $dto->password()
        );

        $this->repository->save($customer);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditCustomerDTO::class;
    }
}