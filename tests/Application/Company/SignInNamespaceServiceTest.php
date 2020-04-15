<?php


namespace Test\Application\Company;


use App\Application\Company\SignIn\SignInNamespaceDTO;
use App\Application\Company\SignIn\SignInNamespaceService;
use App\Domain\Company\Company;
use App\Domain\Company\CompanyNotFound;
use App\Domain\Company\CompanyRepository;
use App\Domain\ValueObject\EmailAddress;
use App\Infrastructure\Persistence\InMemory\InMemoryCompanyRepository;
use App\Infrastructure\Symfony\Factory\URLFactory;
use PHPUnit\Framework\TestCase;

class SignInNamespaceServiceTest extends TestCase
{
    private CompanyRepository $repository;
    private SignInNamespaceService $handler;
    private URLFactory $urlFactory;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCompanyRepository([
            new Company('testing', 'Testing', new EmailAddress('testing@email.com'))
        ]);
        $this->urlFactory = new URLFactory('http://{namespace}.crm.localhost');
        $this->handler = new SignInNamespaceService($this->repository, $this->urlFactory);
    }

    public function testGetAnURI(): void
    {
        $namespace = 'testing';
        $oldDto = new SignInNamespaceDTO($namespace);
        $this->handler->__invoke($oldDto);

        $this->assertEquals($oldDto->namespace(), $namespace);
        $this->assertEquals($oldDto->uri(), 'http://testing.crm.localhost');

        $company = $this->repository->findOneByNamespace($namespace);
        $this->assertNotNull($company);
        $this->assertEquals($oldDto->namespace(), $company->namespace());
    }

    public function testErrorWithNonExistsNamespace(): void
    {
        $namespace = 'hola';
        $oldDto = new SignInNamespaceDTO($namespace);
        $this->expectException(CompanyNotFound::class);
        $this->handler->__invoke($oldDto);
    }

}