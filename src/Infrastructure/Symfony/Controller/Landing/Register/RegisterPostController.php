<?php

namespace App\Infrastructure\Symfony\Controller\Landing\Register;

use App\Application\Company\Create\CreateCompanyDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RegisterPostController extends WebController
{
    public function view(Request $request): RedirectResponse
    {
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('register', $validationErrors, $request)
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            'namespace'     => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type('alnum')],
            'name'          => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Type('string')],
            'email_address' => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Email()],
            'password'      => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $command = new CreateCompanyDTO(
            $request->request->get('namespace'),
            $request->request->get('name'),
            $request->request->get('email_address'),
            $request->request->get('password')
        );

        $this->dispatch($command);

        //TODO: Generate a session

        return $this->redirectWithMessage(
            'dashboard',
            'Compañia %s creada',
            $command->name()
        );
    }
}
