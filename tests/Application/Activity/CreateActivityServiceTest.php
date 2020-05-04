<?php


namespace Test\Application\Activity;


use App\Application\Activity\Create\CreateActivityDTO;
use App\Application\Activity\Create\ActivityCreateService;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class CreateActivityServiceTest extends TestCase
{
    private InMemoryActivityRepository $repository;
    private ActivityCreateService $handler;
    private InMemoryCategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryActivityRepository([]);
        $this->categoryRepository = new InMemoryCategoryRepository([
            new Category(1, 'Outdoor'),
            new Category(2, 'Indoor')
        ]);
        $this->handler = new ActivityCreateService($this->repository, $this->categoryRepository);
    }

    public function testCreateActivity(): void
    {
        $name = 'Fitness';
        $startAt = date('Y-m-d');
        $finishAt = date('Y-m-d', strtotime('tomorrow'));

        $category = $this->categoryRepository->findOneById(1);

        $this->createActivity($name, $startAt, $finishAt, $category->id());

        $list = $this->repository->findAll();
        $activity = array_pop($list);

        $this->assertEquals($name, $activity->name());
        $this->assertEquals($startAt, $activity->startAt()->format('Y-m-d'));
        $this->assertEquals($finishAt, $activity->finishAt()->format('Y-m-d'));
        $this->assertEquals($category, $activity->category());

        $this->repository->remove($activity);
    }

    public function testFailWhenCategoryNotExists(): void
    {
        $name = 'Fitness';
        $startAt = date('Y-m-d');
        $finishAt = date('Y-m-d', strtotime('tomorrow'));
        $fakeCategory = 3;

        $category = $this->categoryRepository->findOneById($fakeCategory);
        $this->assertNull($category);

        try {
            $this->createActivity($name, $startAt, $finishAt, $fakeCategory);
            $this->fail('The category does not exists!!');
        } catch (CategoryNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function createActivity(
        string $name,
        string $startAt,
        string $finishAt,
        int $category
    ): void
    {
        $dto = new CreateActivityDTO(
            $name,
            $startAt,
            $finishAt,
            $category
        );

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

}