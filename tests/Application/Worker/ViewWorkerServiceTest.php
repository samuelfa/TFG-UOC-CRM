<?php


namespace Test\Application\Worker;


use App\Application\Worker\View\WorkerViewService;
use App\Application\Worker\View\ViewWorkerDTO;
use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use PHPUnit\Framework\TestCase;

class ViewWorkerServiceTest extends TestCase
{
    private InMemoryWorkerRepository $repository;
    private WorkerViewService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryWorkerRepository([]);
        $this->handler = new WorkerViewService($this->repository);
    }

    public function testViewWorker(): void
    {
        $nif = '12345678Z';
        $this->createWorker(new NIF($nif), 'password', 'one.email@dot.com');

        $oneWorker = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Worker::class, $oneWorker);

        $dto = new ViewWorkerDTO($nif);
        $worker = $this->handler->__invoke($dto);
        $this->assertEquals($oneWorker, $worker);

        $this->repository->remove($worker);
    }

    public function testWorkerNotFound(): void
    {
        $nif = '12345678Z';

        $oneWorker = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($oneWorker);

        $dto = new ViewWorkerDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('It suppose to not find any worker');
        } catch (WorkerNotFound $exception){
        }
    }

    private function createWorker($nif, $password, $emailAddress): void
    {
        $worker = new Worker(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($worker);
    }
}