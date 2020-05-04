<?php


namespace Test\Application\Activity;


use App\Application\Activity\Edit\ActivityEditService;
use App\Application\Activity\Edit\EditActivityDTO;
use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCategoryRepository;
use PHPUnit\Framework\TestCase;

class EditActivityServiceTest extends TestCase
{
    private InMemoryActivityRepository $repository;
    private ActivityEditService $handler;
    private InMemoryCategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryActivityRepository([
            new Activity(1, 'Mus', new \DateTime(), new \DateTime(), new Category(1, 'Indoor'))
        ]);
        $this->categoryRepository = new InMemoryCategoryRepository([
            new Category(1, 'Outdoor'),
            new Category(2, 'Indoor')
        ]);
        $this->handler = new ActivityEditService($this->repository, $this->categoryRepository);
    }

    public function testEditActivity(): void
    {
        $id = 1;

        $activity = $this->repository->findOneById($id);
        $oldActivity = clone $activity;

        $category = $this->categoryRepository->findOneById(2);

        $name = 'Fitness';
        $startAt = date('Y-m-d');
        $finishAt = date('Y-m-d', strtotime('tomorrow'));

        $this->editActivity($id, $name, $startAt, $finishAt, $category->id());

        $activity = $this->repository->findOneById($id);

        $this->assertEquals($name, $activity->name());
        $this->assertEquals($startAt, $activity->startAt()->format('Y-m-d'));
        $this->assertEquals($finishAt, $activity->finishAt()->format('Y-m-d'));
        $this->assertEquals($category, $activity->category());

        $this->assertEquals($oldActivity->id(), $activity->id());
        $this->assertNotEquals($name, $oldActivity->name());
        $this->assertNotEquals($oldActivity->startAt(), $activity->startAt());
        $this->assertNotEquals($oldActivity->finishAt(), $activity->finishAt());
        $this->assertNotEquals($category, $oldActivity->category());

        $this->repository->remove($activity);
    }

    public function testFailWhenCategoryNotExists(): void
    {
        $id = 1;
        $name = 'Fitness';
        $startAt = date('Y-m-d');
        $finishAt = date('Y-m-d', strtotime('tomorrow'));
        $fakeCategory = 3;

        $category = $this->categoryRepository->findOneById($fakeCategory);
        $this->assertNull($category);

        try {
            $this->editActivity($id, $name, $startAt, $finishAt, $fakeCategory);
            $this->fail('The category does not exist!!');
        } catch (CategoryNotFound $exception){
            $this->assertTrue(true);
        }
    }

    public function testFailWhenActivityNotExists(): void
    {
        $id = 3;
        $name = 'Fitness';
        $startAt = date('Y-m-d');
        $finishAt = date('Y-m-d', strtotime('tomorrow'));
        $category = 1;

        $category = $this->categoryRepository->findOneById($category);
        $this->assertNotNull($category);

        try {
            $this->editActivity($id, $name, $startAt, $finishAt, $category->id());
            $this->fail('The activity does not exist!!');
        } catch (ActivityNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function editActivity(
        int $id,
        string $name,
        string $startAt,
        string $finishAt,
        int $category
    ): void
    {
        $dto = new EditActivityDTO(
            $id,
            $name,
            $startAt,
            $finishAt,
            $category
        );

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

}