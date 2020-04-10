<?php


namespace App\Infrastructure\Symfony\Validator\Constraints;


use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CSRFValidator extends ConstraintValidator
{
    private CsrfTokenManagerInterface $manager;

    public function __construct(CsrfTokenManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CSRF) {
            throw new UnexpectedTypeException($constraint, CSRF::class);
        }

        $status = $this->manager->isTokenValid(new CsrfToken($constraint->id, $value));
        if(!$status){
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }
}