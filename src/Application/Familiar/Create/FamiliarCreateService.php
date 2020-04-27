<?php


namespace App\Application\Familiar\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsNif;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarCreateService implements TransactionalService
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
        /** @var CreateFamiliarDTO $dto */
        $nif = $dto->nif();

        if($this->repository->findOneByNif($nif)){
            throw new AlreadyExistsNif($nif);
        }

        $customer = $this->customerRepository->findOneByNif($dto->customer());
        if(!$customer){
            throw new CustomerNotFound($dto->customer());
        }

        $familiar = Familiar::create(
            $nif,
            $customer,
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait()
        );

        $this->repository->save($familiar);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return CreateFamiliarDTO::class;
    }
}