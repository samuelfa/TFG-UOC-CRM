<?php


namespace Test\Application\Activity;


use App\Application\Activity\View\ActivityViewService;
use App\Application\Activity\View\ViewActivityDTO;
use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Category\Category;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use PHPUnit\Framework\TestCase;

class ViewActivityServiceTest extends TestCase
{
    private InMemoryActivityRepository $repository;
    private ActivityViewService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryActivityRepository([]);
        $this->handler = new ActivityViewService($this->repository);
    }

    public function testViewActivity(): void
    {
        $category = new Category(1, 'Indoor');
        $this->createActivity(1, 'Mus', new \DateTimeImmutable(), new \DateTimeImmutable(), $category);

        $oneActivity = $this->repository->findOneById(1);
        $this->assertInstanceOf(Activity::class, $oneActivity);

        $dto = new ViewActivityDTO(1);
        $activity = $this->handler->__invoke($dto);
        $this->assertEquals($oneActivity, $activity);

        $this->repository->remove($activity);
    }

    public function testActivityNotFound(): void
    {
        $oneActivity = $this->repository->findOneById(3);
        $this->assertNull($oneActivity);

        $dto = new ViewActivityDTO(3);
        try {
            $this->handler->__invoke($dto);
            $this->fail('It suppose to not find any activity');
        } catch (ActivityNotFound $exception){
        }
    }

    private function createActivity(int $id, string $name, \DateTimeImmutable $startAt, \DateTimeImmutable $finishAt, Category $category): void
    {
        $activity = new Activity($id, $name, $startAt, $finishAt, $category);
        $this->repository->save($activity);
    }
}