<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\Create\CreateFamiliarDTO;
use App\Application\Familiar\Create\FamiliarCreateService;
use App\Domain\AlreadyExistsNif;
use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerNotFound;
use App\Domain\Familiar\Familiar;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use PHPUnit\Framework\TestCase;

class CreateFamiliarServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private FamiliarCreateService $handler;
    private InMemoryCustomerRepository $customerRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->handler = new FamiliarCreateService($this->repository, $this->customerRepository);
    }

    public function testCreateFamiliar(): void
    {
        $nif = '12345678Z';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $customerNif = '14756785P';

        $this->createCustomer($customerNif, 'password','one.email@dot.com');
        $familiar = $this->createFamiliar($nif, $name, $surname, $birthday, $portrait, $customerNif);

        $this->assertEquals($nif, $familiar->nif()->value());
        $this->assertEquals($name, $familiar->name());
        $this->assertEquals($surname, $familiar->surname());
        $this->assertEquals($birthday, $familiar->birthday()->format('Y-m-d'));
        $this->assertEquals($portrait, $familiar->portrait()->value());
        $this->assertEquals($customerNif, $familiar->customer()->nif()->value());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($familiar->customer());
    }

    public function testFailWhenNifIsAlreadyInUse(): void
    {
        $nif = '12345678Z';
        $name = 'John';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $customerNif = '14756785P';

        $this->createCustomer($customerNif, 'password','one.email@dot.com');
        $firstFamiliar = $this->createFamiliar($nif, $name, $surname, $birthday, $portrait, $customerNif);

        $nif = '12345678Z';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';

        try {
            $this->createFamiliar($nif, $name, $surname, $birthday, $portrait, $customerNif);
            $this->fail('Not allowed to generate another familiar with the same NIF');
        } catch (AlreadyExistsNif $exception){
        }

        $this->repository->remove($firstFamiliar);
        $this->customerRepository->remove($firstFamiliar->customer());
    }

    public function testFailWhenCustomerNotFound(): void
    {
        $nif = '12345678Z';
        $name = 'Another';
        $surname = 'Doe';
        $birthday = '1970-01-01';
        $portrait = 'https://i.imgur.com/6QEBfT2.jpg';
        $customerNif = '14756785P';

        try {
            $this->createFamiliar($nif, $name, $surname, $birthday, $portrait, $customerNif);
            $this->fail('Not allowed to generate a familiar with no exist customer nif');
        } catch (CustomerNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function createFamiliar(
        string $nif,
        string $name,
        string $surname,
        string $birthday,
        string $portrait,
        string $customerNif
    ): Familiar
    {
        $dto = new CreateFamiliarDTO(
            $nif,
            $name,
            $surname,
            $birthday,
            $portrait,
            $customerNif
        );

        $this->handler->__invoke($dto);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        return $this->repository->findOneByNif(new NIF($nif));
    }

    private function createCustomer($nif, $password, $emailAddress): void
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->customerRepository->save($customer);
    }

}