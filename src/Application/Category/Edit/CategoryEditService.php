<?php


namespace App\Application\Category\Edit;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Category\CategoryNotFound;
use App\Domain\Category\CategoryRepository;

class CategoryEditService implements TransactionalService
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var EditCategoryDTO $dto */
        $id = $dto->id();
        $name = $dto->name();

        $category = $this->repository->findOneById($id);
        if(!$category){
            throw new CategoryNotFound($id);
        }

        $category->update($name);

        $this->repository->save($category);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return EditCategoryDTO::class;
    }
}