<?php


namespace Test\Application\Company;


use App\Application\Company\Create\CreateCompanyDTO;
use App\Application\Company\Create\CreateCompanyService;
use App\Domain\Company\CompanyRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCompanyRepository;
use PHPUnit\Framework\TestCase;

class CreateCompanyServiceTest extends TestCase
{
    private CompanyRepository $repository;
    private CreateCompanyService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCompanyRepository();
        $this->handler = new CreateCompanyService($this->repository);
    }

    public function testCreateACompanyWithValidEmail(): void
    {
        $namespace = 'company';
        $companyName = 'MyCompanyName';
        $emailAddressValue = 'one.email@gmail.com';
        $password = 'password';
        $oldDto = new CreateCompanyDTO($namespace, $companyName, $emailAddressValue, $password);
        $this->handler->execute($oldDto);

        $this->assertEquals($oldDto->namespace(), $namespace);
        $this->assertEquals($oldDto->name(), $companyName);
        $this->assertEquals($oldDto->emailAddress(), $emailAddressValue);

        $company = $this->repository->findOneByNamespace($namespace);
        $this->assertNotNull($company);
        $this->assertEquals($oldDto->name(), $company->name());
        $this->assertEquals($oldDto->emailAddress(), $company->emailAddress()->value());
    }

}