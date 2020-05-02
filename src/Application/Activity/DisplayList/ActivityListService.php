<?php


namespace App\Application\Activity\DisplayList;


use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityRepository;

class ActivityListService
{
    private ActivityRepository $repository;

    public function __construct(ActivityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Activity[]
     */
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}