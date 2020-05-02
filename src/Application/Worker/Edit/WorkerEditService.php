<?php


namespace App\Application\Worker\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\AlreadyExistsEmailAddress;
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
        $emailAddress = $dto->emailAddress();

        $worker = $this->repository->findOneByNif($nif);
        if(!$worker){
            throw new WorkerNotFound($nif);
        }

        $currentEmailAddress = $worker->emailAddress();
        if(!$currentEmailAddress->equals($emailAddress) && $this->repository->findOneByEmailAddress($emailAddress)){
            throw new AlreadyExistsEmailAddress($emailAddress);
        }

        $worker->update(
            $emailAddress,
            $dto->name(),
            $dto->surname(),
            $dto->birthday(),
            $dto->portrait(),
            $dto->password()
        );

        $this->repository->save($worker);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditWorkerDTO::class;
    }
}