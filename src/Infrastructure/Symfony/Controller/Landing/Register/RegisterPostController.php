<?php

namespace App\Infrastructure\Symfony\Controller\Landing\Register;

use App\Application\Company\Create\CreateCompanyDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Security\AuthenticateAfterRegister;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use App\Infrastructure\Symfony\Validator\Constraints\NIF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RegisterPostController extends WebController
{
    public function view(Request $request, AuthenticateAfterRegister $authenticatorHandler): RedirectResponse
    {
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('register', $validationErrors, $request)
            : $this->executeService($request, $authenticatorHandler);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('register')],
            'namespace'     => [new Assert\NotBlank(), new Assert\Length(['min' => 4, 'max' => 50]), new Assert\Type('alnum')],
            'name'          => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 150]), new Assert\Type('string')],
            'nif'           => [new NIF()],
            'email_address' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 150]), new Assert\Email()],
            'password'      => [new Assert\NotBlank(), new Assert\Length(['min' => 4, 'max' => 50]), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request, AuthenticateAfterRegister $authenticatorHandler): RedirectResponse
    {
        $command = new CreateCompanyDTO(
            $request->request->get('namespace'),
            $request->request->get('name'),
            $request->request->get('nif'),
            $request->request->get('email_address'),
            $request->request->get('password')
        );

        $this->dispatch($command);

        $authenticatorHandler->authenticate($command->nif(), $request);

        return $this->redirectWithMessage(
            'crm_dashboard',
            'Compañia %s creada',
            $command->name()
        );
    }
}
