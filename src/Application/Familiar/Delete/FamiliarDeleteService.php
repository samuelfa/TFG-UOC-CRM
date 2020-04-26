<?php


namespace App\Application\Familiar\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarDeleteService implements TransactionalService
{
    private FamiliarRepository $repository;

    public function __construct(FamiliarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteFamiliarDTO $dto */
        $familiar = $this->repository->findOneByNif($dto->nif());
        if(!$familiar){
            throw new FamiliarNotFound($dto->nif());
        }

        $this->repository->remove($familiar);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteFamiliarDTO::class;
    }
}