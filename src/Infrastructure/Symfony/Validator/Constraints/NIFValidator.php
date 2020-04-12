<?php


namespace App\Infrastructure\Symfony\Validator\Constraints;

use NifValidator\NifValidator as NifValidatorService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NIFValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof NIF) {
            throw new UnexpectedTypeException($constraint, NIF::class);
        }

        if (!NifValidatorService::isValid($value)) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }
}