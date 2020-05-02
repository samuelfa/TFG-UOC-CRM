<?php


namespace App\Application\Activity\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Category\CategoryNotFound;
use App\Domain\Category\CategoryRepository;

class ActivityEditService implements TransactionalService
{
    private ActivityRepository $repository;
    private CategoryRepository $categoryRepository;

    public function __construct(ActivityRepository $repository, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditActivityDTO $dto */
        $id = $dto->id();
        $categoryId = $dto->category();

        $activity = $this->repository->findOneById($id);
        if(!$activity){
            throw new ActivityNotFound($id);
        }

        $category = $this->categoryRepository->findOneById($categoryId);
        if(!$category){
            throw new CategoryNotFound($categoryId);
        }

        $activity->update(
            $dto->name(),
            $dto->startAt(),
            $dto->finishAt(),
            $category
        );

        $this->repository->save($activity);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditActivityDTO::class;
    }
}