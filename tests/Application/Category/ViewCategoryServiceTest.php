<?php


namespace Test\Application\Category;


use App\Application\Category\View\CategoryViewService;
use App\Application\Category\View\ViewCategoryDTO;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class ViewCategoryServiceTest extends TestCase
{
    private InMemoryCategoryRepository $repository;
    private CategoryViewService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCategoryRepository([]);
        $this->handler = new CategoryViewService($this->repository);
    }

    public function testViewCategory(): void
    {
        $this->createCategory(1, 'Indoor');

        $oneCategory = $this->repository->findOneById(1);
        $this->assertInstanceOf(Category::class, $oneCategory);

        $dto = new ViewCategoryDTO(1);
        $category = $this->handler->__invoke($dto);
        $this->assertEquals($oneCategory, $category);

        $this->repository->remove($category);
    }

    public function testCategoryNotFound(): void
    {
        $oneCategory = $this->repository->findOneById(3);
        $this->assertNull($oneCategory);

        $dto = new ViewCategoryDTO(3);
        try {
            $this->handler->__invoke($dto);
            $this->fail('It suppose to not find any category');
        } catch (CategoryNotFound $exception){
        }
    }

    private function createCategory(int $id, string $name): void
    {
        $category = new Category($id, $name);
        $this->repository->save($category);
    }
}