<?php


namespace Test\Application\Manager;


use App\Application\Manager\DisplayList\ManagerListService;
use App\Domain\Employee\Manager;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use PHPUnit\Framework\TestCase;

class ListManagersServiceTest extends TestCase
{
    private InMemoryManagerRepository $repository;
    private ManagerListService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryManagerRepository([]);
        $this->handler = new ManagerListService($this->repository);
    }

    public function testDeleteManager(): void
    {
        $nifValues = ['12345678Z', '61011902D', '39115577Y', 'Z0362318G'];
        foreach ($nifValues as $value){
            $this->createManager(new NIF($value), 'password', 'one.email@dot.com');
        }

        $expectedList = $this->repository->findAll();
        $this->assertCount(4, $expectedList);

        $givenList = $this->handler->__invoke();
        $this->assertEquals($expectedList, $givenList);

        $counter = 0;
        foreach ($givenList as $manager){
            $this->assertInstanceOf(Manager::class, $manager);
            $this->assertEquals($nifValues[$counter], $manager->nif()->value());
            $counter++;
        }
    }

    private function createManager($nif, $password, $emailAddress): void
    {
        $manager = new Manager(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($manager);
    }
}