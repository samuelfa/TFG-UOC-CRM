<?php


namespace App\Application\Familiar\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsNif;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarCreateService implements TransactionalService
{
    private FamiliarRepository $repository;

    public function __construct(FamiliarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var CreateFamiliarDTO $dto */
        $nif = $dto->nif();

        if($this->repository->findOneByNif($nif)){
            throw new AlreadyExistsNif($nif);
        }

        $familiar = Familiar::create(
            $nif,
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