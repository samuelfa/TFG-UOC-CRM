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
            public function dispatch(object $event): void
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
        $dto = new CreateCompanyDTO(
            $namespace,
            $companyName,
            $nif,
            $emailAddressValue,
            $password
        );
        $this->handler->__invoke($dto);

        $this->assertEquals($dto->namespace(), $namespace);
        $this->assertEquals($dto->name(), $companyName);
        $this->assertEquals($dto->nif()->value(), $nif);
        $this->assertEquals($dto->emailAddress(), $emailAddressValue);
        $this->assertNotEquals($dto->password()->value(), $password);
        $this->assertTrue(password_verify($password, $dto->password()->value()));

        $company = $this->repository->findOneByNamespace($namespace);
        $this->assertNotNull($company);
        $this->assertEquals($dto->name(), $company->name());
        $this->assertEquals($dto->emailAddress()->value(), $company->emailAddress()->value());
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
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

        $dto = new CreateCompanyDTO(
            $namespace,
            $companyName,
            $nif,
            $emailAddressValue,
            $password
        );

        $company = new Company($dto->namespace(), $companyName, $dto->emailAddress());
        $this->repository->save($company);

        $this->expectException(AlreadyExistsNamespace::class);
        $this->handler->__invoke($dto);
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