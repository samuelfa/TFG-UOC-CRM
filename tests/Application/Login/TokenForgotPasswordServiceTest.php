<?php


namespace Test\Application\Login;


use App\Application\Login\ForgotPasswordEmailExpired;
use App\Application\Login\ForgotPasswordEmailNotFound;
use App\Application\Login\TokenForgotPasswordService;
use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\ValueObject\EmailAddress;
use App\Infrastructure\Persistence\InMemory\InMemoryForgotPasswordEmailRepository;
use PHPUnit\Framework\TestCase;

class TokenForgotPasswordServiceTest extends TestCase
{
    private InMemoryForgotPasswordEmailRepository $forgotPasswordEmailRepository;
    private TokenForgotPasswordService $handler;

    protected function setUp(): void
    {
        $this->forgotPasswordEmailRepository = new InMemoryForgotPasswordEmailRepository([]);
        $this->handler = new TokenForgotPasswordService($this->forgotPasswordEmailRepository);
    }

    public function testViewForgotPassword(): void
    {
        $forgotPasswordEmail = $this->createForgotPasswordEmail('one.email@dot.com');
        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress(new EmailAddress('one.email@dot.com'));
        $this->assertNotNull($element);

        $token = $this->handler->__invoke($forgotPasswordEmail->hash());

        $this->assertEquals($forgotPasswordEmail, $token);

        $this->forgotPasswordEmailRepository->remove($element);
    }

    public function testFailWhenHashNotExists(): void
    {
        $hash = 'hash fake';
        $element = $this->forgotPasswordEmailRepository->findOneByToken($hash);
        $this->assertNull($element);

        try {
            $this->handler->__invoke($hash);
            $this->fail('The token not exists!');
        } catch (ForgotPasswordEmailNotFound $exception){
            $this->assertTrue(true);
        }
    }

    public function testFailWhenIsExpired(): void
    {
        $forgotPasswordEmail = $this->createForgotPasswordEmail('one.email@dot.com');
        $element = $this->forgotPasswordEmailRepository->findOneByEmailAddress(new EmailAddress('one.email@dot.com'));
        $this->assertNotNull($element);

        $token = base64_decode($forgotPasswordEmail->hash());
        $altered = new ForgotPasswordEmail($forgotPasswordEmail->emailAddress(), $token, new \DateTimeImmutable('1970-01-01 00:00:00'));
        $this->forgotPasswordEmailRepository->save($altered);

        try {
            $this->handler->__invoke($forgotPasswordEmail->hash());
            $this->fail('The token is expired!');
        } catch (ForgotPasswordEmailExpired $exception){
            $this->assertTrue(true);
        }

        $element = $this->forgotPasswordEmailRepository->findOneByToken($token);
        $this->assertNull($element);
    }

    private function createForgotPasswordEmail($emailAddress): ForgotPasswordEmail
    {
        $forgotPasswordEmail = ForgotPasswordEmail::create(new EmailAddress($emailAddress));
        $this->forgotPasswordEmailRepository->save($forgotPasswordEmail);
        return $forgotPasswordEmail;
    }
}