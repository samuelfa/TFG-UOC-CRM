<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\Delete\FamiliarDeleteService;
use App\Application\Familiar\Delete\DeleteFamiliarDTO;
use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use PHPUnit\Framework\TestCase;

class DeleteFamiliarServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private InMemoryCustomerRepository $customerRepository;
    private FamiliarDeleteService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->handler = new FamiliarDeleteService($this->repository);
    }

    public function testDeleteFamiliar(): void
    {
        $nif = '12345678Z';

        $customer = $this->createCustomer('14756785P', 'password', 'one.email@dot.com');
        $this->createFamiliar($nif, $customer);

        $familiar = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Familiar::class, $familiar);

        $dto = new DeleteFamiliarDTO($nif);
        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $familiar = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($familiar);
    }

    public function testFailWhenNifNotExists(): void
    {
        $nif = '12345678Z';

        $familiar = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($familiar);

        $dto = new DeleteFamiliarDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('Familiar in repository when it was not expected');
        } catch (FamiliarNotFound $exception){
        }
    }

    private function createFamiliar($nif, Customer $customer): void
    {
        $familiar = new Familiar(new NIF($nif), $customer);
        $this->repository->save($familiar);
    }

    private function createCustomer($nif, $password, $emailAddress): Customer
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->customerRepository->save($customer);
        return $customer;
    }
}