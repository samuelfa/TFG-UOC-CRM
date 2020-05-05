<?php


namespace Test\Application\Category;


use App\Application\Category\DisplayList\CategoryListService;
use App\Domain\Category\Category;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class ListCategoriesServiceTest extends TestCase
{
    private InMemoryCategoryRepository $repository;
    private CategoryListService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCategoryRepository([]);
        $this->handler = new CategoryListService($this->repository);
    }

    public function testListCategories(): void
    {
        $values = [
            1 => 'Indoor',
            2 => 'Outdoor',
            3 => 'Sport',
            4 => 'Cooking'
        ];
        foreach ($values as $key => $name){
            $this->createCategory($key, $name);
        }

        $expectedList = $this->repository->findAll();
        $this->assertCount(4, $expectedList);

        $givenList = $this->handler->__invoke();
        $this->assertEquals($expectedList, $givenList);

        $counter = 1;
        foreach ($givenList as $category){
            $this->assertInstanceOf(Category::class, $category);
            $this->assertEquals($counter, $category->id());
            $this->assertEquals($values[$counter], $category->name());
            $counter++;
        }
    }

    private function createCategory(int $id, string $name): void
    {
        $category = new Category($id, $name);
        $this->repository->save($category);
    }
}