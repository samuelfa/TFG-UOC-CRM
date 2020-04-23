<?php


namespace App\Application\Manager\DisplayList;


use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerRepository;

class ManagerListService
{
    private ManagerRepository $repository;

    public function __construct(ManagerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Manager[]
     */
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}