<?php


namespace Test\Application\Manager;


use App\Application\Manager\View\ManagerViewService;
use App\Application\Manager\View\ViewManagerDTO;
use App\Domain\Employee\Manager;
use App\Domain\Employee\ManagerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use PHPUnit\Framework\TestCase;

class ViewManagerServiceTest extends TestCase
{
    private InMemoryManagerRepository $repository;
    private ManagerViewService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryManagerRepository([]);
        $this->handler = new ManagerViewService($this->repository);
    }

    public function testViewManager(): void
    {
        $nif = '12345678Z';
        $this->createManager(new NIF($nif), 'password', 'one.email@dot.com');

        $oneManager = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Manager::class, $oneManager);

        $dto = new ViewManagerDTO($nif);
        $manager = $this->handler->__invoke($dto);
        $this->assertEquals($oneManager, $manager);

        $this->repository->remove($manager);
    }

    public function testManagerNotFound(): void
    {
        $nif = '12345678Z';

        $oneManager = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($oneManager);

        $dto = new ViewManagerDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('It suppose to not find any manager');
        } catch (ManagerNotFound $exception){
        }
    }

    private function createManager($nif, $password, $emailAddress): void
    {
        $manager = new Manager(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($manager);
    }
}