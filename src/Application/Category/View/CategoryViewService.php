<?php


namespace App\Application\Category\View;


use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFound;
use App\Domain\Category\CategoryRepository;

class CategoryViewService
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ViewCategoryDTO $dto): Category
    {
        $activity = $this->repository->findOneById($dto->id());
        if(!$activity){
            throw new CategoryNotFound($dto->id());
        }

        return $activity;
    }
}