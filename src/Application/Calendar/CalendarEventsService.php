<?php


namespace App\Application\Calendar;


use App\Application\CalendarTransform;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Familiar\Action\Action;

class CalendarEventsService
{
    private ActivityRepository $repository;
    private CalendarTransform $transform;

    public function __construct(ActivityRepository $repository, CalendarTransform $transform)
    {
        $this->repository = $repository;
        $this->transform = $transform;
    }

    /**
     * @return Action[]
     */
    public function __invoke(CalendarEventsDTO $dto): array
    {
        $activities = $this->repository->findByDates($dto->start(), $dto->end());

        return $this->transform->toArray($activities);
    }
}