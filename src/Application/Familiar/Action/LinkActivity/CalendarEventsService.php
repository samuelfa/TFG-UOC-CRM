<?php


namespace App\Application\Familiar\Action\LinkActivity;


use App\Application\CalendarTransform;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Familiar\Action\Action;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class CalendarEventsService
{
    private FamiliarRepository $familiarRepository;
    private ActivityRepository $repository;
    private CalendarTransform $transform;

    public function __construct(FamiliarRepository $familiarRepository, ActivityRepository $repository, CalendarTransform $transform)
    {
        $this->familiarRepository = $familiarRepository;
        $this->repository = $repository;
        $this->transform = $transform;
    }

    /**
     * @return Action[]
     */
    public function __invoke(CalendarEventsDTO $dto): array
    {
        $familiar = $this->familiarRepository->findOneByNif($dto->nif());
        if(!$familiar){
            throw new FamiliarNotFound($dto->nif());
        }

        $activities = $this->repository->findByFamiliarAndDates($familiar, $dto->start(), $dto->end());

        return $this->transform->toArray($activities);
    }
}