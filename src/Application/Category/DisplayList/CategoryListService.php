<?php


namespace App\Application\Category\DisplayList;


use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepository;

class CategoryListService
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Category[]
     */
    public function __invoke(): array
    {
        return $this->repository->findAll();
    }
}