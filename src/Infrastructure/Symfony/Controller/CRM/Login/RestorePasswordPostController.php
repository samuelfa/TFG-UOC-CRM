<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Login;

use App\Application\Login\Employee\RestorePasswordDTO;
use App\Application\Login\TokenNotFound;
use App\Domain\EmailAddressNotFound;
use App\Infrastructure\Symfony\Controller\AnonymousController;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RestorePasswordPostController extends WebController implements AnonymousController
{
    public function view(string $token, Request $request): RedirectResponse
    {
        $request->request->set('token', $token);
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_forgot_password')
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token' => [new CSRF('restore-password')],
            'password'    => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 300]), new Assert\Type('string')],
            'token'       => [new Assert\NotBlank(), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $dto = new RestorePasswordDTO(
            $request->request->get('token'),
            $request->request->get('password')
        );
        try {
            $this->dispatch($dto);
        } catch (TokenNotFound|EmailAddressNotFound $exception){
            return $this->redirectWithError('Impossible to restore the password', $request, 'crm_login');
        }

        return $this->redirectWithMessage('Email sent, please check your inbox', 'crm_login');
    }
}
