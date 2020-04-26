<?php


namespace App\Application\Manager\View;


use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerRepository;

class ManagerViewService
{
    private ManagerRepository $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewManagerDTO $dto): ?Manager
    {
        return $this->repository->findOneByNif($dto->nif());
    }
}