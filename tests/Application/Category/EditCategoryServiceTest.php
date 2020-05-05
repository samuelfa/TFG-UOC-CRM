<?php


namespace Test\Application\Category;


use App\Application\Category\Edit\CategoryEditService;
use App\Application\Category\Edit\EditCategoryDTO;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class EditCategoryServiceTest extends TestCase
{
    private InMemoryCategoryRepository $repository;
    private CategoryEditService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCategoryRepository([
            new Category(1, 'Outdoor'),
            new Category(2, 'Indoor')
        ]);
        $this->handler    = new CategoryEditService($this->repository);
    }

    public function testEditCategory(): void
    {
        $id = 1;

        $category = $this->repository->findOneById($id);
        $oldCategory = clone $category;

        $name = 'Sport';

        $this->editCategory($id, $name);

        $category = $this->repository->findOneById($id);

        $this->assertEquals($name, $category->name());

        $this->assertEquals($oldCategory->id(), $category->id());
        $this->assertNotEquals($name, $oldCategory->name());

        $this->repository->remove($category);
    }

    public function testFailWhenCategoryNotExists(): void
    {
        $id = 3;
        $name = 'Sport';
        $category = 1;

        $category = $this->repository->findOneById($category);
        $this->assertNotNull($category);

        try {
            $this->editCategory($id, $name);
            $this->fail('The category does not exist!!');
        } catch (CategoryNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function editCategory(int $id, string $name): void
    {
        $dto = new EditCategoryDTO($id, $name);

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

}