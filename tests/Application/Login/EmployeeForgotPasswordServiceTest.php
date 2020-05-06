<?php


namespace Test\Application\Login;


use App\Application\Login\Employee\ForgotPasswordDTO;
use App\Application\Login\Employee\ForgotPasswordService;
use App\Domain\EmailAddressNotFound;
use App\Domain\Employee\Manager;
use App\Domain\Employee\Worker;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryForgotPasswordEmailRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use App\Infrastructure\Symfony\Event\ForgotPassword\ForgotPasswordEmailEventDispatcher;
use PHPUnit\Framework\TestCase;
use Test\Application\DummyDispatcher;

class EmployeeForgotPasswordServiceTest extends TestCase
{
    private DummyDispatcher $dispatcher;
    private InMemoryWorkerRepository $workerRepository;
    private InMemoryManagerRepository $managerRepository;
    private InMemoryForgotPasswordEmailRepository $forgotPasswordEmailRepository;
    private ForgotPasswordService $handler;

    protected function setUp(): void
    {
        $this->dispatcher = new DummyDispatcher();
        $forgotPasswordEmailDispatcher = new ForgotPasswordEmailEventDispatcher($this->dispatcher);
        $this->workerRepository = new InMemoryWorkerRepository([]);
        $this->managerRepository = new InMemoryManagerRepository([]);
        $this->forgotPasswordEmailRepository = new InMemoryForgotPasswordEmailRepository([]);
        $this->handler = new ForgotPasswordService($this->workerRepository, $this->managerRepository, $this->forgotPasswordEmailRepository, $forgotPasswordEmailDispatcher);
    }

    public function testRequestForNewPasswordByManager(): void
    {
        $manager = $this->createManager('12345678Z', 'password', 'one.email@dot.com');
        $dto = new ForgotPasswordDTO($manager->emailAddress());

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($manager->emailAddress());
        $this->assertNull($element);

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($manager->emailAddress());
        $this->assertNotNull($element);

        $this->assertEquals($manager->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertTrue($element->isActive());

        $now = new \DateTime();
        $now->add(new \DateInterval('P7D'));

        $this->assertTrue($now > $element->expiresAt());
        $this->assertEquals(1, $this->dispatcher->total());

        $this->managerRepository->remove($manager);
        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testRequestForNewPasswordByWorker(): void
    {
        $worker = $this->createWorker('12345678Z', 'password', 'one.email@dot.com');
        $dto = new ForgotPasswordDTO($worker->emailAddress());

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($worker->emailAddress());
        $this->assertNull($element);

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($worker->emailAddress());
        $this->assertNotNull($element);

        $this->assertEquals($worker->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertTrue($element->isActive());

        $now = new \DateTime();
        $now->add(new \DateInterval('P7D'));

        $this->assertTrue($now > $element->expiresAt());
        $this->assertEquals(1, $this->dispatcher->total());

        $this->workerRepository->remove($worker);
        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testRequestForNewPasswordTwoTimesByManager(): void
    {
        $manager = $this->createManager('12345678Z', 'password', 'one.email@dot.com');
        $dto = new ForgotPasswordDTO($manager->emailAddress());

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($manager->emailAddress());
        $this->assertNull($element);

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($manager->emailAddress());

        $this->assertEquals($manager->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertTrue($element->isActive());

        $now = new \DateTime();
        $now->add(new \DateInterval('P7D'));

        $this->assertTrue($now > $element->expiresAt());
        $this->assertEquals(1, $this->dispatcher->total());

        $oldElement = clone $element;

        $this->handler->__invoke($dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($manager->emailAddress());

        $this->assertEquals($oldElement->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertNotEquals($oldElement->hash(), $element->hash());
        $this->assertNotEquals($oldElement->expiresAt(), $element->expiresAt());

        $this->managerRepository->remove($manager);
        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testRequestForNewPasswordTwoTimesByWorker(): void
    {
        $worker = $this->createWorker('12345678Z', 'password', 'one.email@dot.com');
        $dto = new ForgotPasswordDTO($worker->emailAddress());

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($worker->emailAddress());
        $this->assertNull($element);

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($worker->emailAddress());

        $this->assertEquals($worker->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertTrue($element->isActive());

        $now = new \DateTime();
        $now->add(new \DateInterval('P7D'));

        $this->assertTrue($now > $element->expiresAt());
        $this->assertEquals(1, $this->dispatcher->total());

        $oldElement = clone $element;

        $this->handler->__invoke($dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($worker->emailAddress());

        $this->assertEquals($oldElement->emailAddress(), $element->emailAddress());
        $this->assertNotEmpty($element->hash());
        $this->assertNotEquals($oldElement->hash(), $element->hash());
        $this->assertNotEquals($oldElement->expiresAt(), $element->expiresAt());

        $this->workerRepository->remove($worker);
        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testFailWhenEmailAddressNotExists(): void
    {
        $emailAddress = 'one.email@dot.com';
        $manager = $this->managerRepository->findOneByEmailAddress(new EmailAddress($emailAddress));
        $this->assertNull($manager);

        $worker = $this->workerRepository->findOneByEmailAddress(new EmailAddress($emailAddress));
        $this->assertNull($worker);

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

    private function createManager($nif, $password, $emailAddress): Manager
    {
        $manager = new Manager(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->managerRepository->save($manager);

        return $manager;
    }

    private function createWorker($nif, $password, $emailAddress): Worker
    {
        $worker = new Worker(new NIF($nif), Password::encode($password), new EmailAddress($emailAddress));
        $this->workerRepository->save($worker);

        return $worker;
    }
}