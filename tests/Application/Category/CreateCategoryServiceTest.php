<?php


namespace Test\Application\Category;


use App\Application\Category\Create\CreateCategoryDTO;
use App\Application\Category\Create\CategoryCreateService;
use App\Domain\Category\AlreadyExistsCategory;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class CreateCategoryServiceTest extends TestCase
{
    private InMemoryCategoryRepository $repository;
    private CategoryCreateService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCategoryRepository([]);
        $this->handler = new CategoryCreateService($this->repository);
    }

    public function testCreateCategory(): void
    {
        $name = 'Fitness';

        $this->createCategory($name);

        $list = $this->repository->findAll();
        $category = array_pop($list);

        $this->assertEquals($name, $category->name());

        $this->repository->remove($category);
    }

    public function testFailWhenCategoryNameIsInUse(): void
    {
        $name = 'Fitness';
        $this->createCategory($name);

        $category = $this->repository->findOneByName($name);
        $this->assertNotNull($category);

        try {
            $this->createCategory($name);
            $this->fail('The category is already in use!!');
        } catch (AlreadyExistsCategory $exception){
            $this->assertTrue(true);
        }
    }

    private function createCategory(string $name): void
    {
        $dto = new CreateCategoryDTO($name);

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

}