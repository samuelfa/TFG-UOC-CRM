<?php


namespace Test\Application\Category;


use App\Application\Category\Delete\CategoryDeleteService;
use App\Application\Category\Delete\DeleteCategoryDTO;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class DeleteCategoryServiceTest extends TestCase
{
    private InMemoryCategoryRepository $repository;
    private CategoryDeleteService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCategoryRepository([]);
        $this->handler = new CategoryDeleteService($this->repository);
    }

    public function testDeleteCategory(): void
    {
        $id = 1;

        $this->createCategory($id, 'Mus');

        $category = $this->repository->findOneById($id);
        $this->assertInstanceOf(Category::class, $category);

        $dto = new DeleteCategoryDTO($id);
        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $category = $this->repository->findOneById($id);
        $this->assertNull($category);
    }

    public function testFailWhenNifNotExists(): void
    {
        $id = 2;
        $category = $this->repository->findOneById($id);
        $this->assertNull($category);

        $dto = new DeleteCategoryDTO($id);
        try {
            $this->handler->__invoke($dto);
            $this->fail('Category in repository when it was not expected');
        } catch (CategoryNotFound $exception){
        }
    }

    private function createCategory(int $id, string $name): void
    {
        $category = new Category($id, $name);
        $this->repository->save($category);
    }
}