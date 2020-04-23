<?php


namespace App\Application\Worker\DisplayList;


use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerRepository;

class WorkerListService
{
    private WorkerRepository $repository;

    public function __construct(WorkerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Worker[]
     */
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}