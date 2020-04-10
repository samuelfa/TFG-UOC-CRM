<?php

namespace App\Infrastructure\Symfony\Controller\CRM\ForgotPassword;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ForgotPasswordPostController extends WebController
{
    public function view(Request $request): RedirectResponse
    {
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('forgot-password', $validationErrors, $request)
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            'namespace'     => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type('alnum')],
            'email_address' => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Email()],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        //todo: code to restore password
    }
}
