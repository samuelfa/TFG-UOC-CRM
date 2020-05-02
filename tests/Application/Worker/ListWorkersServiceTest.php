<?php


namespace Test\Application\Worker;


use App\Application\Worker\DisplayList\WorkerListService;
use App\Domain\Employee\Worker;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use PHPUnit\Framework\TestCase;

class ListWorkersServiceTest extends TestCase
{
    private InMemoryWorkerRepository $repository;
    private WorkerListService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryWorkerRepository([]);
        $this->handler = new WorkerListService($this->repository);
    }

    public function testDeleteWorker(): void
    {
        $nifValues = ['12345678Z', '61011902D', '39115577Y', 'Z0362318G'];
        foreach ($nifValues as $value){
            $this->createWorker(new NIF($value), 'password', 'one.email@dot.com');
        }

        $expectedList = $this->repository->findAll();
        $this->assertCount(4, $expectedList);

        $givenList = $this->handler->__invoke();
        $this->assertEquals($expectedList, $givenList);

        $counter = 0;
        foreach ($givenList as $worker){
            $this->assertInstanceOf(Worker::class, $worker);
            $this->assertEquals($nifValues[$counter], $worker->nif()->value());
            $counter++;
        }
    }

    private function createWorker($nif, $password, $emailAddress): void
    {
        $worker = new Worker(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($worker);
    }
}