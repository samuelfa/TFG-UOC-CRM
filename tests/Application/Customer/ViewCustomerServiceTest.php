<?php


namespace Test\Application\Customer;


use App\Application\Customer\View\CustomerViewService;
use App\Application\Customer\View\ViewCustomerDTO;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use PHPUnit\Framework\TestCase;

class ViewCustomerServiceTest extends TestCase
{
    private InMemoryCustomerRepository $repository;
    private CustomerViewService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCustomerRepository([]);
        $this->handler = new CustomerViewService($this->repository);
    }

    public function testViewCustomer(): void
    {
        $nif = '12345678Z';
        $this->createCustomer(new NIF($nif), 'password', 'one.email@dot.com');

        $oneCustomer = $this->repository->findOneByNif(new NIF($nif));
        $this->assertInstanceOf(Customer::class, $oneCustomer);

        $dto = new ViewCustomerDTO($nif);
        $customer = $this->handler->__invoke($dto);
        $this->assertEquals($oneCustomer, $customer);

        $this->repository->remove($customer);
    }

    private function createCustomer($nif, $password, $emailAddress): void
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($customer);
    }

    public function testCustomerNotFound(): void
    {
        $nif = '12345678Z';

        $oneCustomer = $this->repository->findOneByNif(new NIF($nif));
        $this->assertNull($oneCustomer);

        $dto = new ViewCustomerDTO($nif);
        try {
            $this->handler->__invoke($dto);
            $this->fail('It suppose to not find any customer');
        } catch (CustomerNotFound $exception){
        }
    }
}