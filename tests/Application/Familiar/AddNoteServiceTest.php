<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\Action\AddNote\AddNoteDTO;
use App\Application\Familiar\Action\AddNote\AddNoteService;
use App\Domain\Customer\Customer;
use App\Domain\Employee\Manager;
use App\Domain\Employee\Worker;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryNoteRepository;
use PHPUnit\Framework\TestCase;

class AddNoteServiceTest extends TestCase
{
    private InMemoryFamiliarRepository $repository;
    private AddNoteService $handler;
    private InMemoryCustomerRepository $customerRepository;
    private InMemoryNoteRepository $noteRepository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->noteRepository = new InMemoryNoteRepository([]);
        $this->handler = new AddNoteService($this->repository, $this->noteRepository);
    }

    public function testAddNoteByManager(): void
    {
        $manager = Manager::create(new NIF('59247017Y'), new EmailAddress('one.email@gmail.com'), Password::encode('password'));
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $familiar = $this->createFamiliar('12345678Z', $customer);

        $notes = $this->noteRepository->findByFamiliar($familiar);

        $this->assertCount(0, $notes);

        $message = "My new note";
        $isPrivate = true;
        $dto = new AddNoteDTO($familiar->nif(), $message, $isPrivate, $manager);

        $this->handler->__invoke($dto);

        $notes = $this->noteRepository->findByFamiliar($familiar);

        $this->assertCount(1, $notes);

        [$note] = $notes;

        $this->assertEquals($familiar, $note->familiar());
        $this->assertEquals($message, $note->message());
        $this->assertEquals($isPrivate, $note->isPrivate());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($customer);
        $this->noteRepository->remove($note);
    }

    public function testAddNoteByWorker(): void
    {
        $worker = Worker::create(new NIF('59247017Y'), new EmailAddress('one.email@gmail.com'), Password::encode('password'));
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $familiar = $this->createFamiliar('12345678Z', $customer);

        $notes = $this->noteRepository->findByFamiliar($familiar);

        $this->assertCount(0, $notes);

        $message = "My new note";
        $isPrivate = true;
        $dto = new AddNoteDTO($familiar->nif(), $message, $isPrivate, $worker);

        $this->handler->__invoke($dto);

        $notes = $this->noteRepository->findByFamiliar($familiar);

        $this->assertCount(1, $notes);

        [$note] = $notes;

        $this->assertEquals($familiar, $note->familiar());
        $this->assertEquals($message, $note->message());
        $this->assertEquals($isPrivate, $note->isPrivate());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($customer);
        $this->noteRepository->remove($note);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

    public function testAddNoteByCustomer(): void
    {
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $familiar = $this->createFamiliar('12345678Z', $customer);

        $notes = $this->noteRepository->findByFamiliar($familiar);

        $this->assertCount(0, $notes);

        $message = "My new note";
        $isPrivate = true;
        $dto = new AddNoteDTO($familiar->nif(), $message, $isPrivate, $customer);

        $this->handler->__invoke($dto);

        $notes = $this->noteRepository->findByFamiliar($familiar);

        $this->assertCount(1, $notes);

        [$note] = $notes;

        $this->assertEquals($familiar, $note->familiar());
        $this->assertEquals($message, $note->message());
        $this->assertNotEquals($isPrivate, $note->isPrivate());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($customer);
        $this->noteRepository->remove($note);
    }

    public function testFailWhenFamiliarNotFound(): void
    {
        $oneUser = Worker::create(new NIF('59247017Y'), new EmailAddress('one.email@gmail.com'), Password::encode('password'));
        $familiar = $this->repository->findOneByNif(new NIF('12345678Z'));
        $this->assertNull($familiar);

        $message = "My new note";
        $isPrivate = true;
        $dto = new AddNoteDTO('12345678Z', $message, $isPrivate, $oneUser);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The familiar not exists!');
        } catch (FamiliarNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function createFamiliar($nif, Customer $customer): Familiar
    {
        $familiar = new Familiar(new NIF($nif), $customer);
        $this->repository->save($familiar);
        return $familiar;
    }

    private function createCustomer($nif, $password, $emailAddress): Customer
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->customerRepository->save($customer);
        return $customer;
    }
}