<?php


namespace App\Application\Activity\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Activity\ActivityRepository;

class ActivityDeleteService implements TransactionalService
{
    private ActivityRepository $repository;

    public function __construct(ActivityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteActivityDTO $dto */
        $activity = $this->repository->findOneById($dto->id());
        if(!$activity){
            throw new ActivityNotFound($dto->id());
        }

        $this->repository->remove($activity);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteActivityDTO::class;
    }
}