<?php

namespace App\Domain\Login;


use App\Domain\Repository;
use App\Domain\ValueObject\EmailAddress;

interface ForgotPasswordEmailRepository extends Repository
{
    public function findOneByToken(string $token): ?ForgotPasswordEmail;
    public function findOneByEmailAddress(EmailAddress $emailAddress): ?ForgotPasswordEmail;
    public function save(ForgotPasswordEmail $forgotPasswordEmail): void;
    public function remove(ForgotPasswordEmail $forgotPasswordEmail): void;
}
