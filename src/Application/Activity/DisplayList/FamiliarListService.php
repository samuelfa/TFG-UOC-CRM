<?php


namespace App\Application\Activity\DisplayList;


use App\Domain\Activity\Activity;
use App\Domain\Familiar\Action\LinkActivityRepository;
use App\Domain\Familiar\Familiar;

class FamiliarListService
{
    private LinkActivityRepository $repository;

    public function __construct(LinkActivityRepository $activityRepository)
    {
        $this->repository = $activityRepository;
    }

    /**
     * @return Familiar[]
     */
    public function __invoke(Activity $activity): array
    {
        $list = [];
        $linkedList = $this->repository->findByActivity($activity);
        foreach ($linkedList as $linkActivity){
            $list[] = $linkActivity->familiar();
        }

        return $list;
    }
}