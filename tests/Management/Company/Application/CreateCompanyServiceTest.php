<?php


namespace Test\Management\Company\Application;


use App\Management\Company\Application\Create\CreateCompanyDTO;
use App\Management\Company\Application\Create\CreateCompanyService;
use App\Management\Company\Domain\CompanyRepository;
use App\Management\Company\Infrastructure\Persistence\InMemory\InMemoryCompanyRepository;
use App\Shared\Domain\Factory\UuidFactory;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Symfony\Factory\UuidFactory as SymfonyUuidFactory;
use PHPUnit\Framework\TestCase;

class CreateCompanyServiceTest extends TestCase
{
    private CompanyRepository $repository;
    private UuidFactory $factory;
    private CreateCompanyService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCompanyRepository();
        $this->factory = new SymfonyUuidFactory();
        $this->handler = new CreateCompanyService($this->repository, $this->factory);
    }

    public function testCreateACompanyWithValidEmail(): void
    {
        $oldDto = new CreateCompanyDTO('John Doe', 'one.email@gmail.com');
        $newDto = $this->handler->execute($oldDto);

        $this->assertNotEquals($oldDto->uuid(), $newDto->uuid());
        $this->assertEquals($oldDto->name(), $newDto->name());
        $this->assertEquals($oldDto->emailAddress(), $newDto->emailAddress());

        $uuid = new Uuid($newDto->uuid());

        $company = $this->repository->findOneById($uuid);
        $this->assertNotNull($company);
        $this->assertEquals($newDto->name(), $company->name());
        $this->assertEquals($newDto->emailAddress(), $company->emailAddress()->value());
    }

}