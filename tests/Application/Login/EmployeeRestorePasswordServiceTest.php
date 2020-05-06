<?php


namespace Test\Application\Login;


use App\Application\Login\Employee\RestorePasswordDTO;
use App\Application\Login\Employee\RestorePasswordService;
use App\Application\Login\TokenNotFound;
use App\Domain\EmailAddressNotFound;
use App\Domain\Employee\Manager;
use App\Domain\Employee\Worker;
use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\ValueObject\NIF;
use App\Domain\ValueObject\Password;
use App\Infrastructure\Persistence\InMemory\InMemoryForgotPasswordEmailRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryManagerRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryWorkerRepository;
use PHPUnit\Framework\TestCase;

class EmployeeRestorePasswordServiceTest extends TestCase
{
    private InMemoryWorkerRepository $workerRepository;
    private InMemoryManagerRepository $managerRepository;
    private InMemoryForgotPasswordEmailRepository $forgotPasswordEmailRepository;
    private RestorePasswordService $handler;

    protected function setUp(): void
    {
        $this->workerRepository = new InMemoryWorkerRepository([]);
        $this->managerRepository = new InMemoryManagerRepository([]);
        $this->forgotPasswordEmailRepository = new InMemoryForgotPasswordEmailRepository([]);
        $this->handler = new RestorePasswordService($this->workerRepository, $this->managerRepository, $this->forgotPasswordEmailRepository);
    }

    public function testRestorePasswordByManager(): void
    {
        $forgotPasswordEmail = $this->createForgotPasswordEmail('one.email@dot.com');

        $newPassword = 'holita vecinito';
        $manager = $this->createManager('12345678Z', 'password', 'one.email@dot.com');
        $dto = new RestorePasswordDTO($forgotPasswordEmail->hash(), $newPassword);
        $oldManager = clone $manager;

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($manager->emailAddress());
        $this->assertNull($element);

        $manager = $this->managerRepository->findOneByEmailAddress($forgotPasswordEmail->emailAddress());

        $this->assertEquals($oldManager->emailAddress(), $manager->emailAddress());
        $this->assertNotEquals($oldManager->password(), $manager->password());
        $this->assertTrue(password_verify($newPassword, $manager->password()));

        $this->managerRepository->remove($manager);
    }

    public function testRestorePasswordByWorker(): void
    {
        $forgotPasswordEmail = $this->createForgotPasswordEmail('one.email@dot.com');

        $newPassword = 'holita vecinito';
        $worker = $this->createWorker('12345678Z', 'password', 'one.email@dot.com');
        $dto = new RestorePasswordDTO($forgotPasswordEmail->hash(), $newPassword);
        $oldWorker = clone $worker;

        $this->handler->__invoke($dto);
        $this->assertInstanceOf($this->handler->subscribeTo(), $dto);

        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress($worker->emailAddress());
        $this->assertNull($element);

        $worker = $this->workerRepository->findOneByEmailAddress($forgotPasswordEmail->emailAddress());

        $this->assertEquals($oldWorker->emailAddress(), $worker->emailAddress());
        $this->assertNotEquals($oldWorker->password(), $worker->password());
        $this->assertTrue(password_verify($newPassword, $worker->password()));

        $this->workerRepository->remove($worker);
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

        $manager = $this->managerRepository->findOneByEmailAddress(new EmailAddress('one.email@dot.com'));
        $this->assertNull($manager);

        $worker = $this->workerRepository->findOneByEmailAddress(new EmailAddress('one.email@dot.com'));
        $this->assertNull($worker);

        try {
            $this->handler->__invoke($dto);
            $this->fail('The email address not exists as employee!');
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

    private function createForgotPasswordEmail($emailAddress): ForgotPasswordEmail
    {
        $forgotPasswordEmail = ForgotPasswordEmail::create(new EmailAddress($emailAddress));
        $this->forgotPasswordEmailRepository->save($forgotPasswordEmail);
        return $forgotPasswordEmail;
    }
}