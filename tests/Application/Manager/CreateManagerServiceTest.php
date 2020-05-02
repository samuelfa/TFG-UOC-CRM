<?php


namespace Test\Application\Manager;


use App\Application\Manager\Create\CreateManagerDTO;
use App\Application\Manager\Create\ManagerCreateService;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\AlreadyExistsNif;
use App\Domain\Employee\Manager;
use App\Domain\ValueObject\NIF;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use PHPUnit\Framework\TestCase;

class CreateManagerServiceTest extends TestCase
{
    private InMemoryManagerRepository $repository;
    private ManagerCreateService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryManagerRepository([]);
        $this->handler = new ManagerCreateService($this->repository);
    }

    public function testCreateManager(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        $manager = $this->createManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $manager->nif()->value());
        $this->assertEquals($emailAddressValue, $manager->emailAddress()->value());
        $this->assertEquals($name, $manager->name());
        $this->assertEquals($surname, $manager->surname());
        $this->assertEquals($birthday, $manager->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $manager->portrait()->value());

        $this->repository->remove($manager);
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

        $this->createManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $nif = '12345678Z';
        $emailAddressValue = 'other.email@gmail.com';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        try {
            $this->createManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to generate to managers with the same NIF');
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

        $this->createManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $nif = '61011902D';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        try {
            $this->createManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to generate to managers with the same NIF');
        } catch (AlreadyExistsEmailAddress $exception){
        }
    }

    private function createManager(
        string $nif,
        string $emailAddressValue,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password): Manager
    {
        $dto = new CreateManagerDTO(
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

        $manager = $this->repository->findOneByNif(new NIF($nif));

        $this->assertEquals($dto->password()->value(), $manager->password()->value());

        return $manager;
    }

}