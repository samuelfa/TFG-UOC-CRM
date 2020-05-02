<?php


namespace App\Application\Activity\View;


use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Activity\ActivityRepository;

class ActivityViewService
{
    private ActivityRepository $repository;

    public function __construct(ActivityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewActivityDTO $dto): Activity
    {
        $activity = $this->repository->findOneById($dto->id());
        if(!$activity){
            throw new ActivityNotFound($dto->id());
        }

        return $activity;
    }
}