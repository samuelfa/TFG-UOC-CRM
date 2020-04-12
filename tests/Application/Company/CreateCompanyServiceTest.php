<?php


namespace Test\Application\Company;


use App\Application\Company\Create\AlreadyExistsNamespace;
use App\Application\Company\Create\CreateCompanyDTO;
use App\Application\Company\Create\CreateCompanyService;
use App\Domain\Company\Company;
use App\Domain\Company\CompanyRepository;
use App\Domain\ValueObject\InvalidEmailAddressException;
use App\Domain\ValueObject\InvalidNifException;
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
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $password = 'password';
        $oldDto = new CreateCompanyDTO(
            $namespace,
            $companyName,
            $nif,
            $emailAddressValue,
            $password
        );
        $this->handler->__invoke($oldDto);

        $this->assertEquals($oldDto->namespace(), $namespace);
        $this->assertEquals($oldDto->name(), $companyName);
        $this->assertEquals($oldDto->nif()->value(), $nif);
        $this->assertEquals($oldDto->emailAddress(), $emailAddressValue);
        $this->assertNotEquals($oldDto->password()->value(), $password);
        $this->assertTrue(password_verify($password, $oldDto->password()->value()));

        $company = $this->repository->findOneByNamespace($namespace);
        $this->assertNotNull($company);
        $this->assertEquals($oldDto->name(), $company->name());
        $this->assertEquals($oldDto->emailAddress()->value(), $company->emailAddress()->value());
    }

    public function testFailWhenNifIsInvalid(): void
    {
        $namespace = 'company';
        $companyName = 'MyCompanyName';
        $nif = 'gsgg';
        $emailAddressValue = 'one.email@gmail.com';
        $password = 'password';
        $this->expectException(InvalidNifException::class);
        new CreateCompanyDTO(
            $namespace,
            $companyName,
            $nif,
            $emailAddressValue,
            $password
        );
    }

    public function testFailWhenNamespaceIsAlreadyInUse(): void
    {
        $namespace = 'company';
        $companyName = 'MyCompanyName';
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $password = 'password';

        $oldDto = new CreateCompanyDTO(
            $namespace,
            $companyName,
            $nif,
            $emailAddressValue,
            $password
        );

        $company = new Company($oldDto->namespace(), $companyName, $oldDto->emailAddress());
        $this->repository->save($company);

        $this->expectException(AlreadyExistsNamespace::class);
        $this->handler->__invoke($oldDto);
    }

    public function testFailWhenEmailIsInvalid(): void
    {
        $namespace = 'company';
        $companyName = 'MyCompanyName';
        $nif = '12345678Z';
        $emailAddressValue = 'one.email.gmail.com';
        $password = 'password';
        $this->expectException(InvalidEmailAddressException::class);
        new CreateCompanyDTO(
            $namespace,
            $companyName,
            $nif,
            $emailAddressValue,
            $password
        );
    }

}