<?php


namespace App\Application\Worker\View;


use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerNotFound;
use App\Domain\Employee\WorkerRepository;

class WorkerViewService
{
    private WorkerRepository $repository;

    public function __construct(WorkerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewWorkerDTO $dto): Worker
    {
        $worker = $this->repository->findOneByNif($dto->nif());
        if(!$worker){
            throw new WorkerNotFound($dto->nif());
        }

        return $worker;
    }
}