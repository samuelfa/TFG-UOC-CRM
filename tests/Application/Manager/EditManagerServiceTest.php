<?php


namespace Test\Application\Manager;


use App\Application\Manager\Edit\ManagerEditService;
use App\Application\Manager\Edit\EditManagerDTO;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use PHPUnit\Framework\TestCase;

class EditManagerServiceTest extends TestCase
{
    private InMemoryManagerRepository $repository;
    private ManagerEditService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryManagerRepository([]);
        $this->handler = new ManagerEditService($this->repository);
    }

    public function testEditManager(): void
    {
        $nif = '12345678Z';

        $this->createManager($nif, 'password', 'one.email@dot.com');

        $manager = $this->repository->findOneByNif(new NIF($nif));
        $oldManager = clone $manager;

        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        $this->editManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $manager->nif()->value());
        $this->assertEquals($emailAddressValue, $manager->emailAddress()->value());
        $this->assertEquals($name, $manager->name());
        $this->assertEquals($surname, $manager->surname());
        $this->assertEquals($birthday, $manager->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $manager->portrait()->value());

        $this->assertTrue($oldManager->nif()->equals($manager->nif()));
        $this->assertFalse($oldManager->emailAddress()->equals($manager->emailAddress()));
        $this->assertFalse($oldManager->password()->equals($manager->password()));
        $this->assertNotEquals($name, $oldManager->name());
        $this->assertNotEquals($surname, $oldManager->surname());
        $this->assertNull($oldManager->birthday());
        $this->assertNull($oldManager->portrait());

        $this->repository->remove($manager);
    }

    public function testEditManagerWithSameEmailAddress(): void
    {
        $nif = '12345678Z';

        $this->createManager($nif, 'password', 'one.email@dot.com');

        $manager = $this->repository->findOneByNif(new NIF($nif));
        $oldManager = clone $manager;

        $emailAddressValue = 'one.email@dot.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        $this->editManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $manager->nif()->value());
        $this->assertEquals($emailAddressValue, $manager->emailAddress()->value());
        $this->assertEquals($name, $manager->name());
        $this->assertEquals($surname, $manager->surname());
        $this->assertEquals($birthday, $manager->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $manager->portrait()->value());

        $this->assertTrue($oldManager->nif()->equals($manager->nif()));
        $this->assertTrue($oldManager->emailAddress()->equals($manager->emailAddress()));
        $this->assertFalse($oldManager->password()->equals($manager->password()));
        $this->assertNotEquals($name, $oldManager->name());
        $this->assertNotEquals($surname, $oldManager->surname());
        $this->assertNull($oldManager->birthday());
        $this->assertNull($oldManager->portrait());

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
        $password = 'another-password';

        try {
            $this->editManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('The manager should to not be in the repository');
        } catch (ManagerNotFound $exception){
        }
    }

    public function testFailWhenEmailAddressIsAlreadyInUse(): void
    {
        $nif = '12345678Z';

        $this->createManager($nif, 'password', 'one.email@dot.com');
        $this->createManager('86829384Z', 'password', 'other.email@dot.com');

        $emailAddressValue = 'other.email@dot.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        try {
            $this->editManager($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to edit a manager with the same email address of another manager');
        } catch (AlreadyExistsEmailAddress $exception){
        }
    }

    private function createManager($nif, $password, $emailAddress): void
    {
        $manager = new Manager(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($manager);
    }

    private function editManager(
        string $nif,
        string $emailAddressValue,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password): void
    {
        $dto = new EditManagerDTO(
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
    }

}