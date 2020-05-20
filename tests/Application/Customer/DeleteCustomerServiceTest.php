<?php


namespace Test\Application\Customer;


use App\Application\Customer\Delete\CustomerDeleteService;
use App\Application\Customer\Delete\DeleteCustomerDTO;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use PHPUnit\Framework\TestCase;

class DeleteCustomerServiceTest extends TestCase
{
    private InMemoryCustomerRepository $repository;
    private CustomerDeleteService $handler;

    protected function setUp(): void
    {
        $this->repository   = new InMemoryCustomerRepository([]);
        $familiarRepository = new InMemoryFamiliarRepository([]);
        $this->handler      = new CustomerDeleteService($this->repository, $familiarRepository);
    }

    public function testDeleteCustomer(): void
    {
        $nif = '12345678Z';

        $this->createCustomer($nif, 'password', 'one.email@dot.com');

        $customer = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Customer::class, $customer);

        $dto = new DeleteCustomerDTO($nif);
        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $customer = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($customer);
    }

    public function testFailWhenNifNotExists(): void
    {
        $nif = '12345678Z';

        $customer = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($customer);

        $dto = new DeleteCustomerDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('Customer in repository when it was not expected');
        } catch (CustomerNotFound $exception){
        }
    }

    private function createCustomer($nif, $password, $emailAddress): void
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($customer);
    }
}