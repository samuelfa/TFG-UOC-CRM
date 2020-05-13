<?php


namespace App\Application\Category\DisplayList;


use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Category\Category;

class ActivityListService
{
    private ActivityRepository $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * @return Activity[]
     */
    public function __invoke(Category $category): array
    {
        return $this->activityRepository->findByCategory($category);
    }
}