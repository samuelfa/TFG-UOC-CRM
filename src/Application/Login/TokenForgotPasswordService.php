<?php


namespace App\Application\Login;


use App\Domain\Login\ForgotPasswordEmailRepository;

class TokenForgotPasswordService
{
    private ForgotPasswordEmailRepository $repository;

    public function __construct(ForgotPasswordEmailRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $value)
    {
        $value = base64_decode($value);
        $token = $this->repository->findOneByToken($value);
        if(!$token){
            throw new ForgotPasswordEmailNotFound($value);
        }

        if(!$token->isActive()){
            $this->repository->remove($token);
            $this->repository->flush();
            throw new ForgotPasswordEmailExpired($value);
        }
        return $token;
    }

}