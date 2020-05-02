<?php


namespace App\Application\Activity\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityRepository;
use App\Domain\Category\CategoryNotFound;
use App\Domain\Category\CategoryRepository;

class ActivityCreateService implements TransactionalService
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
        /** @var CreateActivityDTO $dto */
        $categoryId = $dto->categoryId();

        $category = $this->categoryRepository->findOneById($categoryId);
        if(!$category){
            throw new CategoryNotFound($categoryId);
        }

        $activity = Activity::create(
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
        return CreateActivityDTO::class;
    }
}