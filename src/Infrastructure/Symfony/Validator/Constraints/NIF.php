<?php


namespace App\Infrastructure\Symfony\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class NIF extends Constraint
{
    public string $message = 'Invalid NIF value provided.';
}