<?php


namespace App\Application\Manager\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Employee\ManagerNotFound;
use App\Domain\Employee\ManagerRepository;

class ManagerDeleteService implements TransactionalService
{
    private ManagerRepository $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteManagerDTO $dto */
        $manager = $this->repository->findOneByNif($dto->nif());
        if(!$manager){
            throw new ManagerNotFound($dto->nif());
        }

        $this->repository->remove($manager);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteManagerDTO::class;
    }
}