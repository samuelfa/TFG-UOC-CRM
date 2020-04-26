<?php


namespace App\Application\Worker\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Employee\WorkerNotFound;
use App\Domain\Employee\WorkerRepository;

class WorkerEditService implements TransactionalService
{
    private WorkerRepository $repository;

    public function __construct(WorkerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditWorkerDTO $dto */
        $nif = $dto->nif();

        $worker = $this->repository->findOneByNif($nif);
        if(!$worker){
            throw new WorkerNotFound($nif);
        }

        $worker->update(
            $dto->emailAddress(),
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait()
        );

        $this->repository->save($worker);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditWorkerDTO::class;
    }
}