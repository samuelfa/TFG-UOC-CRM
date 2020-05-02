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
        $category = $this->repository->findOneById($dto->id());
        if(!$category){
            throw new CategoryNotFound($dto->id());
        }

        $this->repository->remove($category);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return DeleteCategoryDTO::class;
    }
}