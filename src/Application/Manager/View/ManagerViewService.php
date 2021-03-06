<?php


namespace App\Application\Manager\View;


use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerNotFound;
use App\Domain\Employee\ManagerRepository;

class ManagerViewService
{
    private ManagerRepository $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewManagerDTO $dto): Manager
    {
        $manager = $this->repository->findOneByNif($dto->nif());
        if(!$manager){
            throw new ManagerNotFound($dto->nif());
        }

        return $manager;
    }
}