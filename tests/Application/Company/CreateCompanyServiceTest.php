<?php


namespace Test\Application\Company;


use App\Application\Company\Create\CreateCompanyDTO;
use App\Application\Company\Create\CreateCompanyService;
use App\Domain\Company\CompanyRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCompanyRepository;
use App\Infrastructure\Symfony\Event\Company\CompanyEventDispatcher;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

class CreateCompanyServiceTest extends TestCase
{
    private CompanyRepository $repository;
    private CreateCompanyService $handler;

    protected function setUp(): void
    {
        $dispatcher = new class implements EventDispatcherInterface {
            public function dispatch(object $event)
            {}
        };
        $companyDispatcher = new CompanyEventDispatcher($dispatcher);
        $this->repository = new InMemoryCompanyRepository([]);
        $this->handler = new CreateCompanyService($this->repository, $companyDispatcher);
    }

    public function testCreateACompanyWithValidEmail(): void
    {
        $namespace = 'company';
        $companyName = 'MyCompanyName';
        $emailAddressValue = 'one.email@gmail.com';
        $password = 'password';
        $oldDto = new CreateCompanyDTO($namespace, $companyName, $emailAddressValue, $password);
        $this->handler->__invoke($oldDto);

        $this->assertEquals($oldDto->namespace(), $namespace);
        $this->assertEquals($oldDto->name(), $companyName);
        $this->assertEquals($oldDto->emailAddress(), $emailAddressValue);

        $company = $this->repository->findOneByNamespace($namespace);
        $this->assertNotNull($company);
        $this->assertEquals($oldDto->name(), $company->name());
        $this->assertEquals($oldDto->emailAddress()->value(), $company->emailAddress()->value());
    }

}