<?php


namespace Test\Application\Customer;


use App\Application\Customer\DisplayList\CustomerListService;
use App\Domain\Customer\Customer;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use PHPUnit\Framework\TestCase;

class ListCustomersServiceTest extends TestCase
{
    private InMemoryCustomerRepository $repository;
    private CustomerListService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCustomerRepository([]);
        $this->handler = new CustomerListService($this->repository);
    }

    public function testListCustomers(): void
    {
        $nifValues = ['12345678Z', '61011902D', '39115577Y', 'Z0362318G'];
        foreach ($nifValues as $value){
            $this->createCustomer(new NIF($value), 'password', 'one.email@dot.com');
        }

        $expectedList = $this->repository->findAll();
        $this->assertCount(4, $expectedList);

        $givenList = $this->handler->__invoke();
        $this->assertEquals($expectedList, $givenList);

        $counter = 0;
        foreach ($givenList as $customer){
            $this->assertInstanceOf(Customer::class, $customer);
            $this->assertEquals($nifValues[$counter], $customer->nif()->value());
            $counter++;
        }
    }

    private function createCustomer($nif, $password, $emailAddress): void
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($customer);
    }
}