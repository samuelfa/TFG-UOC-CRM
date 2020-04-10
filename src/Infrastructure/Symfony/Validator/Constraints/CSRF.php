<?php


namespace App\Infrastructure\Symfony\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class CSRF extends Constraint
{
    public string $message = 'Invalid CSRF token provided.';
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
        parent::__construct();
    }
}