<?php


namespace Test\Application\Login;


use App\Application\Login\Customer\RestorePasswordDTO;
use App\Application\Login\Customer\RestorePasswordService;
use App\Application\Login\TokenNotFound;
use App\Domain\Customer\Customer;
use App\Domain\EmailAddressNotFound;
use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryForgotPasswordEmailRepository;
use PHPUnit\Framework\TestCase;

class CustomerRestorePasswordServiceTest extends TestCase
{
    private InMemoryCustomerRepository $customerRepository;
    private InMemoryForgotPasswordEmailRepository $forgotPasswordEmailRepository;
    private RestorePasswordService $handler;

    protected function setUp(): void
    {
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->forgotPasswordEmailRepository = new InMemoryForgotPasswordEmailRepository([]);
        $this->handler = new RestorePasswordService($this->customerRepository, $this->forgotPasswordEmailRepository);
    }

    public function testRestorePassword(): void
    {
        $forgotPasswordEmail = $this->createForgotPasswordEmail('one.email@dot.com');

        $newPassword = 'holita vecinito';
        $customer = $this->createCustomer('64067012Y', 'password',$forgotPasswordEmail->emailAddress());
        $dto = new RestorePasswordDTO($forgotPasswordEmail->hash(), $newPassword);
        $oldCustomer = clone $customer;

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($customer->emailAddress());
        $this->assertNull($element);

        $customer = $this->customerRepository->findOneByEmailAddress($forgotPasswordEmail->emailAddress());

        $this->assertEquals($oldCustomer->emailAddress(), $customer->emailAddress());
        $this->assertNotEquals($oldCustomer->password(), $customer->password());
        $this->assertTrue(password_verify($newPassword, $customer->password()));

        $this->customerRepository->remove($customer);
    }

    public function testFailWhenTokenNotFound(): void
    {
        $dto = new RestorePasswordDTO('hash fake', 'password');

        $element = $this->forgotPasswordEmailRepository->findOneByToken('hash fake');
        $this->assertNull($element);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The token not exists!');
        } catch (TokenNotFound $exception){
            $this->assertTrue(true);
        }
    }

    public function testFailWhenEmailAddressNotFound(): void
    {
        $forgotPasswordEmail = $this->createForgotPasswordEmail('one.email@dot.com');

        $newPassword = 'holita vecinito';
        $dto = new RestorePasswordDTO($forgotPasswordEmail->hash(), $newPassword);

        $customer = $this->customerRepository->findOneByEmailAddress(new EmailAddress('one.email@dot.com'));
        $this->assertNull($customer);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The email address not exists as customer!');
        } catch (EmailAddressNotFound $exception){
            $this->assertTrue(true);
        }
    }

    private function createCustomer($nif, $password, $emailAddress): Customer
    {
        $customer = new Customer(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->customerRepository->save($customer);
        return $customer;
    }

    private function createForgotPasswordEmail($emailAddress): ForgotPasswordEmail
    {
        $forgotPasswordEmail = ForgotPasswordEmail::create(new EmailAddress($emailAddress));
        $this->forgotPasswordEmailRepository->save($forgotPasswordEmail);
        return $forgotPasswordEmail;
    }
}