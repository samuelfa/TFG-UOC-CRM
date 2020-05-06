<?php


namespace Test\Application\Login;


use App\Application\Login\Customer\ForgotPasswordDTO;
use App\Application\Login\Customer\ForgotPasswordService;
use App\Domain\Customer\Customer;
use App\Domain\EmailAddressNotFound;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryCustomerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryForgotPasswordEmailRepository;
use App\Infrastructure\Symfony\Event\ForgotPassword\ForgotPasswordEmailEventDispatcher;
use PHPUnit\Framework\TestCase;
use Test\Application\DummyDispatcher;

class CustomerForgotPasswordServiceTest extends TestCase
{
    private DummyDispatcher $dispatcher;
    private InMemoryCustomerRepository $customerRepository;
    private InMemoryForgotPasswordEmailRepository $forgotPasswordEmailRepository;
    private ForgotPasswordService $handler;

    protected function setUp(): void
    {
        $this->dispatcher = new DummyDispatcher();
        $forgotPasswordEmailDispatcher = new ForgotPasswordEmailEventDispatcher($this->dispatcher);
        $this->customerRepository = new InMemoryCustomerRepository([]);
        $this->forgotPasswordEmailRepository = new InMemoryForgotPasswordEmailRepository([]);
        $this->handler = new ForgotPasswordService($this->customerRepository, $this->forgotPasswordEmailRepository, $forgotPasswordEmailDispatcher);
    }

    public function testRequestForNewPassword(): void
    {
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $dto = new ForgotPasswordDTO($customer->emailAddress());

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($customer->emailAddress());
        $this->assertNull($element);

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($customer->emailAddress());
        $this->assertNotNull($element);

        $this->assertEquals($customer->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertTrue($element->isActive());

        $now = new \DateTime();
        $now->add(new \DateInterval('P7D'));

        $this->assertTrue($now > $element->expiresAt());
        $this->assertEquals(1, $this->dispatcher->total());

        $this->customerRepository->remove($customer);
        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testRequestForNewPasswordTwoTimes(): void
    {
        $customer = $this->createCustomer('64067012Y', 'password','one.email@dot.com');
        $dto = new ForgotPasswordDTO($customer->emailAddress());

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($customer->emailAddress());
        $this->assertNull($element);

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($customer->emailAddress());

        $this->assertEquals($customer->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertTrue($element->isActive());

        $now = new \DateTime();
        $now->add(new \DateInterval('P7D'));

        $this->assertTrue($now > $element->expiresAt());
        $this->assertEquals(1, $this->dispatcher->total());

        $oldElement = clone $element;

        $this->handler->__invoke($dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($customer->emailAddress());

        $this->assertEquals($oldElement->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertNotEquals($oldElement->hash(), $element->hash());
        $this->assertNotEquals($oldElement->expiresAt(), $element->expiresAt());

        $this->customerRepository->remove($customer);
        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testFailWhenEmailAddressNotExists(): void
    {
        $emailAddress = 'one.email@dot.com';
        $customer = $this->customerRepository->findOneByEmailAddress(new EmailAddress($emailAddress));
        $this->assertNull($customer);

        $dto = new ForgotPasswordDTO($emailAddress);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress(new EmailAddress($emailAddress));
        $this->assertNull($element);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The email address not exists!');
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
}