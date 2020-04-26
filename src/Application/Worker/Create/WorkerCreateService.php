<?php


namespace App\Application\Worker\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsNif;
use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerRepository;

class WorkerCreateService implements TransactionalService
{
    private WorkerRepository $repository;

    public function __construct(WorkerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var CreateWorkerDTO $dto */
        $nif = $dto->nif();

        if($this->repository->findOneByNif($nif)){
            throw new AlreadyExistsNif($nif);
        }

        $worker = Worker::create(
            $nif,
            $dto->emailAddress(),
            $dto->password(),
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
        return CreateWorkerDTO::class;
    }
}