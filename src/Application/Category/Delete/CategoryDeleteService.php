<?php


namespace App\Application\Category\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Category\CategoryLinkedWithActivities;
use App\Domain\Category\CategoryNotFound;
use App\Domain\Category\CategoryRepository;

class CategoryDeleteService implements TransactionalService
{
    private CategoryRepository $repository;
    /**
     * @var ActivityRepository
     */
    private ActivityRepository $activityRepository;

    public function __construct(CategoryRepository $repository, ActivityRepository $activityRepository)
    {
        $this->repository = $repository;
        $this->activityRepository = $activityRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteCategoryDTO $dto */
        $category = $this->repository->findOneById($dto->id());
        if(!$category){
            throw new CategoryNotFound($dto->id());
        }

        $activities = $this->activityRepository->findByCategory($category);
        if(!empty($activities)){
            throw new CategoryLinkedWithActivities($category->id());
        }

        $this->repository->remove($category);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteCategoryDTO::class;
    }
}