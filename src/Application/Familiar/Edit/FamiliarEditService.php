<?php


namespace App\Application\Familiar\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarEditService implements TransactionalService
{
    private FamiliarRepository $repository;

    public function __construct(FamiliarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditFamiliarDTO $dto */
        $nif = $dto->nif();

        $familiar = $this->repository->findOneByNif($nif);
        if(!$familiar){
            throw new FamiliarNotFound($nif);
        }

        $familiar->update(
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
        return EditFamiliarDTO::class;
    }
}