<?php


namespace App\Application\Familiar\DisplayList;


use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarRepository;

class FamiliarListService
{
    private FamiliarRepository $repository;

    public function __construct(FamiliarRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Familiar[]
     */
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}