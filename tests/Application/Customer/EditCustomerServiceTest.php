<?php


namespace Test\Application\Customer;


use App\Application\Customer\Edit\CustomerEditService;
use App\Application\Customer\Edit\EditCustomerDTO;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use PHPUnit\Framework\TestCase;

class EditCustomerServiceTest extends TestCase
{
    private InMemoryCustomerRepository $repository;
    private CustomerEditService $handler;

    protected function setUp(): void
    {
        $this->repository = new InMemoryCustomerRepository([]);
        $this->handler = new CustomerEditService($this->repository);
    }

    public function testEditCustomer(): void
    {
        $nif = '12345678Z';

        $this->createCustomer($nif, 'password', 'one.email@dot.com');

        $customer = $this->repository->findOneByNif(new NIF($nif));
        $oldCustomer = clone $customer;

        $emailAddressValue = 'one.email@gmail.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        $this->editCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $customer->nif()->value());
        $this->assertEquals($emailAddressValue, $customer->emailAddress()->value());
        $this->assertEquals($name, $customer->name());
        $this->assertEquals($surname, $customer->surname());
        $this->assertEquals($birthday, $customer->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $customer->portrait()->value());

        $this->assertTrue($oldCustomer->nif()->equals($customer->nif()));
        $this->assertFalse($oldCustomer->emailAddress()->equals($customer->emailAddress()));
        $this->assertFalse($oldCustomer->password()->equals($customer->password()));
        $this->assertNotEquals($name, $oldCustomer->name());
        $this->assertNotEquals($surname, $oldCustomer->surname());
        $this->assertNull($oldCustomer->birthday());
        $this->assertNull($oldCustomer->portrait());

        $this->repository->remove($customer);
    }

    public function testEditCustomerWithSameEmailAddress(): void
    {
        $nif = '12345678Z';

        $this->createCustomer($nif, 'password', 'one.email@dot.com');

        $customer = $this->repository->findOneByNif(new NIF($nif));
        $oldCustomer = clone $customer;

        $emailAddressValue = 'one.email@dot.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        $this->editCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);

        $this->assertEquals($nif, $customer->nif()->value());
        $this->assertEquals($emailAddressValue, $customer->emailAddress()->value());
        $this->assertEquals($name, $customer->name());
        $this->assertEquals($surname, $customer->surname());
        $this->assertEquals($birthday, $customer->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $customer->portrait()->value());

        $this->assertTrue($oldCustomer->nif()->equals($customer->nif()));
        $this->assertTrue($oldCustomer->emailAddress()->equals($customer->emailAddress()));
        $this->assertFalse($oldCustomer->password()->equals($customer->password()));
        $this->assertNotEquals($name, $oldCustomer->name());
        $this->assertNotEquals($surname, $oldCustomer->surname());
        $this->assertNull($oldCustomer->birthday());
        $this->assertNull($oldCustomer->portrait());

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
        $password = 'another-password';

        try {
            $this->editCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('The customer should to not be in the repository');
        } catch (CustomerNotFound $exception){
        }
    }

    public function testFailWhenEmailAddressIsAlreadyInUse(): void
    {
        $nif = '12345678Z';

        $this->createCustomer($nif, 'password', 'one.email@dot.com');
        $this->createCustomer('86829384Z', 'password', 'other.email@dot.com');

        $emailAddressValue = 'other.email@dot.com';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $password = 'another-password';

        try {
            $this->editCustomer($nif, $emailAddressValue, $name, $surname, $birthday, $portrait, $password);
            $this->fail('Not allowed to edit a customer with the same email address of another customer');
        } catch (AlreadyExistsEmailAddress $exception){
        }
    }

    private function createCustomer($nif, $password, $emailAddress): void
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->repository->save($customer);
    }

    private function editCustomer(
        string $nif,
        string $emailAddressValue,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $password): void
    {
        $dto = new EditCustomerDTO(
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
    }

}