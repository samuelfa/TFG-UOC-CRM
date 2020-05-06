<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Domain\Login\ForgotPasswordEmail;
use App\Domain\Login\ForgotPasswordEmailRepository;
use App\Domain\ValueObject\EmailAddress;

class InMemoryForgotPasswordEmailRepository implements ForgotPasswordEmailRepository
{
    /** @var ForgotPasswordEmail[] */
    private array $list;

    /**
     * @param ForgotPasswordEmail[] $list
     */
    public function __construct(array $list)
    {
        $this->list = [];
        foreach ($list as $element){
            $this->list[$element->emailAddress()->value()] = $element;
        }
    }

    public function findOneByToken(string $token): ?ForgotPasswordEmail
    {
        $hash = base64_encode($token);
        foreach ($this->list as $element){
            if($element->hash() !== $hash){
                continue;
            }

            return $element;
        }

        return null;
    }

    public function findOneByEmailAddress(EmailAddress $emailAddress): ?ForgotPasswordEmail
    {
        foreach ($this->list as $element){
            if(!$element->emailAddress()->equals($emailAddress)){
                continue;
            }

            return $element;
        }

        return null;
    }

    public function save(ForgotPasswordEmail $forgotPasswordEmail): void
    {
        $this->list[$forgotPasswordEmail->emailAddress()->value()] = $forgotPasswordEmail;
    }

    public function remove(ForgotPasswordEmail $forgotPasswordEmail): void
    {
        unset($this->list[$forgotPasswordEmail->emailAddress()->value()]);
    }

    public function flush(): void
    {}
}