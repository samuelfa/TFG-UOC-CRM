<?php


namespace Test\Application\Worker;


use App\Application\Worker\Delete\WorkerDeleteService;
use App\Application\Worker\Delete\DeleteWorkerDTO;
use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use PHPUnit\Framework\TestCase;

class DeleteWorkerServiceTest extends TestCase
{
    private InMemoryWorkerRepository $repository;
    private WorkerDeleteService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryWorkerRepository([]);
        $this->handler = new WorkerDeleteService($this->repository);
    }

    public function testDeleteWorker(): void
    {
        $nif = '12345678Z';

        $this->createWorker($nif, 'password', 'one.email@dot.com');

        $worker = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Worker::class, $worker);

        $dto = new DeleteWorkerDTO($nif);
        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $worker = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($worker);
    }

    public function testFailWhenNifNotExists(): void
    {
        $nif = '12345678Z';

        $worker = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($worker);

        $dto = new DeleteWorkerDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('Worker in repository when it was not expected');
        } catch (WorkerNotFound $exception){
        }
    }

    private function createWorker($nif, $password, $emailAddress): void
    {
        $worker = new Worker(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($worker);
    }
}