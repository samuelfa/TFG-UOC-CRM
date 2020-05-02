<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\Edit\FamiliarEditService;
use App\Application\Familiar\Edit\EditFamiliarDTO;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use PHPUnit\Framework\TestCase;

class EditFamiliarServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private FamiliarEditService $handler;
    private InMemoryCustomerRepository $customerRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->handler = new FamiliarEditService($this->repository, $this->customerRepository);
    }

    public function testEditFamiliar(): void
    {
        $nif = '12345678Z';

        $customer = $this->createCustomer('89725724X', 'password', 'one.email@dot.com');
        $this->createFamiliar($nif, $customer);

        $familiar = $this->repository->findOneByNif(new NIF($nif));
        $oldFamiliar = clone $familiar;

        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';

        $this->editFamiliar($nif, $name, $surname, $birthday, $portrait, $customer->nif());

        $this->assertEquals($nif, $familiar->nif()->value());
        $this->assertTrue($familiar->customer()->nif()->equals($customer->nif()));
        $this->assertEquals($name, $familiar->name());
        $this->assertEquals($surname, $familiar->surname());
        $this->assertEquals($birthday, $familiar->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $familiar->portrait()->value());

        $this->assertTrue($oldFamiliar->nif()->equals($familiar->nif()));
        $this->assertNotEquals($name, $oldFamiliar->name());
        $this->assertNotEquals($surname, $oldFamiliar->surname());
        $this->assertNull($oldFamiliar->birthday());
        $this->assertNull($oldFamiliar->portrait());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($customer);
    }

    public function testFailWhenNifIsUnknown(): void
    {
        $nif = '12345678Z';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $customerNif = '89725724X';

        try {
            $this->editFamiliar($nif, $name, $surname, $birthday, $portrait, $customerNif);
            $this->fail('The familiar should to not be in the repository');
        } catch (FamiliarNotFound $exception){
            $this->assertTrue(true);
        }
    }

    public function testFailWhenCustomerIsUnknown(): void
    {
        $nif = '12345678Z';

        $customer = $this->createCustomer('89725724X', 'password', 'one.email@dot.com');
        $this->createFamiliar($nif, $customer);

        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';

        try {
            $this->editFamiliar($nif, $name, $surname, $birthday, $portrait, '93806413Q');
            $this->fail('Not allowed to set a customer nif unknown');
        } catch (CustomerNotFound $exception){
            $this->assertTrue(true);
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

    private function editFamiliar(
        string $nif,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $customerNif
    ): void
    {
        $dto = new EditFamiliarDTO(
            $nif,
            $name,
            $surname,
            $birthday,
            $portrait,
            $customerNif
        );

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

}