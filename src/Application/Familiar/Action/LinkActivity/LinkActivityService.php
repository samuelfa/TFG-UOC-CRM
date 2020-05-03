<?php


namespace App\Application\Familiar\Action\LinkActivity;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Familiar\Action\LinkActivity;
use App\Domain\Familiar\Action\LinkActivityRepository;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\Familiar\FamiliarRepository;

class LinkActivityService implements TransactionalService
{
    private FamiliarRepository $repository;
    private ActivityRepository $activityRepository;
    private LinkActivityRepository $linkActivityRepository;

    public function __construct(
        FamiliarRepository $repository,
        ActivityRepository $activityRepository,
        LinkActivityRepository $linkActivityRepository
    )
    {
        $this->repository             = $repository;
        $this->activityRepository     = $activityRepository;
        $this->linkActivityRepository = $linkActivityRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var LinkActivityDTO $dto */
        $nif = $dto->nif();
        $activityId = $dto->activity();

        $familiar = $this->repository->findOneByNif($nif);
        if(!$familiar){
            throw new FamiliarNotFound($nif);
        }

        $activity = $this->activityRepository->findOneById($activityId);
        if(!$activity){
            throw new ActivityNotFound($activityId);
        }

        $linkActivity = LinkActivity::create(
            $familiar,
            $activity
        );

        $this->linkActivityRepository->save($linkActivity);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return LinkActivityDTO::class;
    }
}