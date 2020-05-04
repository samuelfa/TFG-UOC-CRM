<?php


namespace Test\Application\Activity;


use App\Application\Activity\Delete\ActivityDeleteService;
use App\Application\Activity\Delete\DeleteActivityDTO;
use App\Domain\Activity\Activity;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Category\Category;
use App\Infrastructure\Persistence\InMemory\InMemoryActivityRepository;
use PHPUnit\Framework\TestCase;

class DeleteActivityServiceTest extends TestCase
{
    private InMemoryActivityRepository $repository;
    private ActivityDeleteService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryActivityRepository([]);
        $this->handler = new ActivityDeleteService($this->repository);
    }

    public function testDeleteActivity(): void
    {
        $id = 1;

        $this->createActivity($id, 'Mus');

        $activity = $this->repository->findOneById($id);
        $this->assertInstanceOf(Activity::class, $activity);

        $dto = new DeleteActivityDTO($id);
        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $activity = $this->repository->findOneById($id);
        $this->assertNull($activity);
    }

    public function testFailWhenNifNotExists(): void
    {
        $id = 2;
        $activity = $this->repository->findOneById($id);
        $this->assertNull($activity);

        $dto = new DeleteActivityDTO($id);
        try {
            $this->handler->__invoke($dto);
            $this->fail('Activity in repository when it was not expected');
        } catch (ActivityNotFound $exception){
        }
    }

    private function createActivity(int $id, string $name): void
    {
        $activity = new Activity($id, $name, new \DateTime(), new \DateTime(), new Category(1, 'Indoor'));
        $this->repository->save($activity);
    }
}