<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Login;

use App\Application\Login\Employee\ForgotPasswordDTO;
use App\Domain\EmailAddressNotFound;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
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
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_forgot_password')
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('forgot-password')],
            'email_address' => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Email()],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $dto = new ForgotPasswordDTO($request->request->get('email_address'));
        try {
            $this->dispatch($dto);
        } catch (EmailAddressNotFound $exception){
        }

        return $this->redirectWithMessage('crm_forgot_password', 'Email sent, please check your inbox');
    }
}
