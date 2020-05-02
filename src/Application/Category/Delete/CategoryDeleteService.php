<?php


namespace App\Application\Category\Delete;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Category\CategoryNotFound;
use App\Domain\Category\CategoryRepository;

class CategoryDeleteService implements TransactionalService
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var DeleteCategoryDTO $dto */
        $category = $this->repository->findOneByName($dto->name());
        if(!$category){
            throw new CategoryNotFound($dto->name());
        }

        $this->repository->remove($category);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteCategoryDTO::class;
    }
}