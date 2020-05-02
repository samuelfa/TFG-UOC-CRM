<?php


namespace Test\Application\Manager;


use App\Application\Manager\Delete\ManagerDeleteService;
use App\Application\Manager\Delete\DeleteManagerDTO;
use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use PHPUnit\Framework\TestCase;

class DeleteManagerServiceTest extends TestCase
{
    private InMemoryManagerRepository $repository;
    private ManagerDeleteService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryManagerRepository([]);
        $this->handler = new ManagerDeleteService($this->repository);
    }

    public function testDeleteManager(): void
    {
        $nif = '12345678Z';

        $this->createManager($nif, 'password', 'one.email@dot.com');

        $manager = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Manager::class, $manager);

        $dto = new DeleteManagerDTO($nif);
        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $manager = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($manager);
    }

    public function testFailWhenNifNotExists(): void
    {
        $nif = '12345678Z';

        $manager = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($manager);

        $dto = new DeleteManagerDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('Manager in repository when it was not expected');
        } catch (ManagerNotFound $exception){
        }
    }

    private function createManager($nif, $password, $emailAddress): void
    {
        $manager = new Manager(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($manager);
    }
}