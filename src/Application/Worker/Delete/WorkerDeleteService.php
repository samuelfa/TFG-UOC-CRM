<?php


namespace App\Application\Worker\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Employee\WorkerNotFound;
use App\Domain\Employee\WorkerRepository;

class WorkerDeleteService implements TransactionalService
{
    private WorkerRepository $repository;

    public function __construct(WorkerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteWorkerDTO $dto */
        $worker = $this->repository->findOneByNif($dto->nif());
        if(!$worker){
            throw new WorkerNotFound($dto->nif());
        }

        $this->repository->remove($worker);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteWorkerDTO::class;
    }
}