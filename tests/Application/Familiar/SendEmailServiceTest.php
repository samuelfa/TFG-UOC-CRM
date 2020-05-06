<?php


namespace Test\Application\Familiar;


use App\Application\Familiar\Action\SendEmail\SendEmailDTO;
use App\Application\Familiar\Action\SendEmail\SendEmailService;
use App\Domain\Customer\Customer;
use App\Domain\Familiar\Familiar;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryEmailRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFamiliarRepository;
use App\Infrastructure\Symfony\Event\FamiliarSendEmail\EmailEventDispatcher;
use PHPUnit\Framework\TestCase;
use Test\Application\DummyDispatcher;

class SendEmailServiceTest extends TestCase
{
    private DummyDispatcher $dispatcher;
    private InMemoryFamiliarRepository $repository;
    private InMemoryCustomerRepository $customerRepository;
    private InMemoryEmailRepository $emailRepository;
    private SendEmailService $handler;

    protected function setUp(): void
    {
        $this->dispatcher = new DummyDispatcher();
        $emailDispatcher = new EmailEventDispatcher($this->dispatcher);
        $this->repository = new InMemoryFamiliarRepository([]);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->emailRepository = new InMemoryEmailRepository([]);
        $this->handler = new SendEmailService($this->repository, $this->emailRepository, $emailDispatcher);
    }

    public function testSendEmail(): void
    {
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $familiar = $this->createFamiliar('12345678Z', $customer);

        $list = $this->emailRepository->findByFamiliar($familiar);

        $this->assertCount(0, $list);

        $subject = "Subject for one email";
        $body = "Hi! This email is to giving thanks for reading this text ;)";
        $recipients = ['my.contact@dot.email.com'];
        $dto = new SendEmailDTO($familiar->nif(), $subject, $body, $recipients);

        $this->handler->__invoke($dto);

        $list = $this->emailRepository->findByFamiliar($familiar);

        $this->assertCount(1, $list);

        [$email] = $list;

        $this->assertEquals($familiar, $email->familiar());
        $this->assertEquals($subject, $email->subject());
        $this->assertEquals($body, $email->body());
        $this->assertEquals($recipients, $email->recipients());

        $this->assertEquals(1, $this->dispatcher->total());

        $this->repository->remove($familiar);
        $this->customerRepository->remove($customer);
        $this->emailRepository->remove($email);

        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);
    }

    public function testFailWhenFamiliarNotFound(): void
    {
        $familiar = $this->repository->findOneByNif(new NIF('12345678Z'));
        $this->assertNull($familiar);

        $subject = "Subject for one email";
        $body = "Hi! This email is to giving thanks for reading this text ;)";
        $recipients = ['my.contact@dot.email.com'];
        $dto = new SendEmailDTO('64067012Y', $subject, $body, $recipients);

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