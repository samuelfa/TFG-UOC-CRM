<?php


namespace Test\Application\Worker;


use App\Application\Worker\Create\CreateWorkerDTO;
use App\Application\Worker\Create\WorkerCreateService;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\AlreadyExistsNif;
use App\Domain\Employee\Worker;
use App\Domain\ValueObject\NIF;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use PHPUnit\Framework\TestCase;

class CreateWorkerServiceTest extends TestCase
{
    private InMemoryWorkerRepository $repository;
    private WorkerCreateService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryWorkerRepository([]);
        $this->handler = new WorkerCreateService($this->repository);
    }

    public function testCreateWorker(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        $worker = $this->createWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $worker->nif()->value());
        $this->assertEquals($emailAddressValue, $worker->emailAddress()->value());
        $this->assertEquals($name, $worker->name());
        $this->assertEquals($surname, $worker->surname());
        $this->assertEquals($birthday, $worker->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $worker->portrait()->value());

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
        $password = 'password';

        $this->createWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $nif = '12345678Z';
        $emailAddressValue = 'other.email@gmail.com';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        try {
            $this->createWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to generate to workers with the same NIF');
        } catch (AlreadyExistsNif $exception){
        }
    }

    public function testFailWhenEmailAddressIsAlreadyInUse(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        $this->createWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $nif = '61011902D';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        try {
            $this->createWorker($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to generate to workers with the same NIF');
        } catch (AlreadyExistsEmailAddress $exception){
        }
    }

    private function createWorker(
        string $nif,
        string $emailAddressValue,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password): Worker
    {
        $dto = new CreateWorkerDTO(
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

        return $worker;
    }

}