<?php


namespace App\Application\Familiar\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarEditService implements TransactionalService
{
    private FamiliarRepository $repository;
    private CustomerRepository $customerRepository;

    public function __construct(
        FamiliarRepository $repository,
        CustomerRepository $customerRepository
    )
    {
        $this->repository = $repository;
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditFamiliarDTO $dto */
        $nif = $dto->nif();

        $familiar = $this->repository->findOneByNif($nif);
        if(!$familiar){
            throw new FamiliarNotFound($nif);
        }

        $customer = $this->customerRepository->findOneByNif($dto->customer());
        if(!$customer){
            throw new CustomerNotFound($dto->customer());
        }

        $familiar->update(
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait(),
            $customer
        );

        $this->repository->save($familiar);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditFamiliarDTO::class;
    }
}