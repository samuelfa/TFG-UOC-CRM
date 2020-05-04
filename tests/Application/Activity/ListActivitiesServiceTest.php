<?php


namespace Test\Application\Activity;


use App\Application\Activity\DisplayList\ActivityListService;
use App\Domain\Activity\Activity;
use App\Domain\Category\Category;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use PHPUnit\Framework\TestCase;

class ListActivitiesServiceTest extends TestCase
{
    private InMemoryActivityRepository $repository;
    private ActivityListService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryActivityRepository([]);
        $this->handler = new ActivityListService($this->repository);
    }

    public function testListActivities(): void
    {
        $values = [
            1 => 'Mus',
            2 => 'Parchis',
            3 => 'Chess',
            4 => 'Ping-Pong'
        ];
        $category = new Category(1, 'Indoor');
        foreach ($values as $key => $name){
            $this->createActivity($key, $name, new \DateTime(), new \DateTime(), $category);
        }

        $expectedList = $this->repository->findAll();
        $this->assertCount(4, $expectedList);

        $givenList = $this->handler->__invoke();
        $this->assertEquals($expectedList, $givenList);

        $counter = 1;
        foreach ($givenList as $activity){
            $this->assertInstanceOf(Activity::class, $activity);
            $this->assertEquals($counter, $activity->id());
            $this->assertEquals($values[$counter], $activity->name());
            $counter++;
        }
    }

    private function createActivity(int $id, string $name, \DateTime $startAt, \DateTime $finishAt, Category $category): void
    {
        $activity = new Activity($id, $name, $startAt, $finishAt, $category);
        $this->repository->save($activity);
    }
}