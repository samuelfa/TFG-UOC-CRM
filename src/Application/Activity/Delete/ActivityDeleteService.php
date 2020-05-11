<?php


namespace App\Application\Activity\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Activity\ActivityLinkedWithFamiliars;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Familiar\Action\LinkActivityRepository;

class ActivityDeleteService implements TransactionalService
{
    private ActivityRepository $repository;
    private LinkActivityRepository $linkActivityRepository;

    public function __construct(ActivityRepository $repository, LinkActivityRepository $linkActivityRepository)
    {
        $this->repository = $repository;
        $this->linkActivityRepository = $linkActivityRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteActivityDTO $dto */
        $activity = $this->repository->findOneById($dto->id());
        if(!$activity){
            throw new ActivityNotFound($dto->id());
        }

        if($this->linkActivityRepository->findByActivity($activity)){
            throw new ActivityLinkedWithFamiliars($dto->id());
        }

        $this->repository->remove($activity);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteActivityDTO::class;
    }
}