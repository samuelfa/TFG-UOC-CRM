<?php


namespace Test\Application\Worker;


use App\Application\Worker\Edit\WorkerEditService;
use App\Application\Worker\Edit\EditWorkerDTO;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\Employee\Worker;
use App\Domain\Employee\WorkerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use PHPUnit\Framework\TestCase;

class EditWorkerServiceTest extends TestCase
{
    private InMemoryWorkerRepository $repository;
    private WorkerEditService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryWorkerRepository([]);
        $this->handler = new WorkerEditService($this->repository);
    }

    public function testEditWorker(): void
    {
        $nif = '12345678Z';

        $this->createWorker($nif, 'password', 'one.email@dot.com');

        $worker = $this->repository->findOneByNif(new NIF($nif));
        $oldWorker = clone $worker;

        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        $this->editWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $worker->nif()->value());
        $this->assertEquals($emailAddressValue, $worker->emailAddress()->value());
        $this->assertEquals($name, $worker->name());
        $this->assertEquals($surname, $worker->surname());
        $this->assertEquals($birthday, $worker->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $worker->portrait()->value());

        $this->assertTrue($oldWorker->nif()->equals($worker->nif()));
        $this->assertFalse($oldWorker->emailAddress()->equals($worker->emailAddress()));
        $this->assertFalse($oldWorker->password()->equals($worker->password()));
        $this->assertNotEquals($name, $oldWorker->name());
        $this->assertNotEquals($surname, $oldWorker->surname());
        $this->assertNull($oldWorker->birthday());
        $this->assertNull($oldWorker->portrait());

        $this->repository->remove($worker);
    }

    public function testEditWorkerWithSameEmailAddress(): void
    {
        $nif = '12345678Z';

        $this->createWorker($nif, 'password', 'one.email@dot.com');

        $worker = $this->repository->findOneByNif(new NIF($nif));
        $oldWorker = clone $worker;

        $emailAddressValue = 'one.email@dot.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        $this->editWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $worker->nif()->value());
        $this->assertEquals($emailAddressValue, $worker->emailAddress()->value());
        $this->assertEquals($name, $worker->name());
        $this->assertEquals($surname, $worker->surname());
        $this->assertEquals($birthday, $worker->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $worker->portrait()->value());

        $this->assertTrue($oldWorker->nif()->equals($worker->nif()));
        $this->assertTrue($oldWorker->emailAddress()->equals($worker->emailAddress()));
        $this->assertFalse($oldWorker->password()->equals($worker->password()));
        $this->assertNotEquals($name, $oldWorker->name());
        $this->assertNotEquals($surname, $oldWorker->surname());
        $this->assertNull($oldWorker->birthday());
        $this->assertNull($oldWorker->portrait());

        $this->repository->remove($worker);
    }

    public function testFailWhenNifIsAlreadyInUse(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        try {
            $this->editWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('The worker should to not be in the repository');
        } catch (WorkerNotFound $exception){
        }
    }

    public function testFailWhenEmailAddressIsAlreadyInUse(): void
    {
        $nif = '12345678Z';

        $this->createWorker($nif, 'password', 'one.email@dot.com');
        $this->createWorker('86829384Z', 'password', 'other.email@dot.com');

        $emailAddressValue = 'other.email@dot.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        try {
            $this->editWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to edit a worker with the same email address of another worker');
        } catch (AlreadyExistsEmailAddress $exception){
        }
    }

    private function createWorker($nif, $password, $emailAddress): void
    {
        $worker = new Worker(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($worker);
    }

    private function editWorker(
        string $nif,
        string $emailAddressValue,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password): void
    {
        $dto = new EditWorkerDTO(
            $nif,
            $emailAddressValue,
            $name,
            $surname,
            $birthday,
            $portrait,
            $password
        );

        $this->assertNotEquals($password, $dto->password()->value());

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $worker = $this->repository->findOneByNif(new NIF($nif));

        $this->assertEquals($dto->password()->value(), $worker->password()->value());
    }

}