<?php


namespace App\Application\Familiar\View;


use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarViewService
{
    private FamiliarRepository $repository;

    public function __construct(FamiliarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewFamiliarDTO $dto): Familiar
    {
        $familiar = $this->repository->findOneByNif($dto->nif());
        if(!$familiar){
            throw new FamiliarNotFound($dto->nif());
        }

        return $familiar;
    }
}