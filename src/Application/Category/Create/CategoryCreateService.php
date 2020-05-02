<?php


namespace App\Application\Category\Create;


use App\Application\DTO;
use App\Application\TransactionalService;
use App\Domain\Category\AlreadyExistsCategory;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryRepository;

class CategoryCreateService implements TransactionalService
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(DTO $dto): DTO
    {
        /** @var CreateCategoryDTO $dto */
        $name = $dto->name();

        if($this->repository->findOneByName($name)){
            throw new AlreadyExistsCategory($name);
        }

        $category = new Category($name);

        $this->repository->save($category);

        return $dto;
    }

    public function subscribeTo(): string
    {
        return CreateCategoryDTO::class;
    }
}