<?php


namespace Test\Application\Customer;


use App\Application\Customer\Create\CreateCustomerDTO;
use App\Application\Customer\Create\CustomerCreateService;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\AlreadyExistsNif;
use App\Domain\Customer\Customer;
use App\Domain\ValueObject\NIF;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use PHPUnit\Framework\TestCase;

class CreateCustomerServiceTest extends TestCase
{
    private InMemoryCustomerRepository $repository;
    private CustomerCreateService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCustomerRepository([]);
        $this->handler = new CustomerCreateService($this->repository);
    }

    public function testCreateCustomer(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        $customer = $this->createCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $customer->nif()->value());
        $this->assertEquals($emailAddressValue, $customer->emailAddress()->value());
        $this->assertEquals($name, $customer->name());
        $this->assertEquals($surname, $customer->surname());
        $this->assertEquals($birthday, $customer->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $customer->portrait()->value());

        $this->repository->remove($customer);
    }

    public function testFailWhenNifIsAlreadyInUse(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        $this->createCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $nif = '12345678Z';
        $emailAddressValue = 'other.email@gmail.com';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        try {
            $this->createCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to generate another customer with the same NIF');
        } catch (AlreadyExistsNif $exception){
        }
    }

    public function testFailWhenEmailAddressIsAlreadyInUse(): void
    {
        $nif = '12345678Z';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        $this->createCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $nif = '61011902D';
        $emailAddressValue = 'one.email@gmail.com';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'password';

        try {
            $this->createCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to generate to customers with the same NIF');
        } catch (AlreadyExistsEmailAddress $exception){
        }
    }

    private function createCustomer(
        string $nif,
        string $emailAddressValue,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password): Customer
    {
        $dto = new CreateCustomerDTO(
            $nif,
            $emailAddressValue,
            $name,
            $surname,
            $birthday,
            $portrait,
            $password
        );

        $this->assertNotEquals($password, $dto->password()->value());

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $customer = $this->repository->findOneByNif(new NIF($nif));

        $this->assertEquals($dto->password()->value(), $customer->password()->value());

        return $customer;
    }

}