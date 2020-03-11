<?php

namespace App\Management\Company\Infrastructure\Symfony\Controller;

use App\Management\Company\Application\Create\CreateCompanyCommand;
use App\Shared\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class RegisterCompanyController extends WebController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validationErrors = $this->validateRequest($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('register_company_form', $validationErrors, $request)
            : $this->createCompany($request);
    }

    private function validateRequest(Request $request): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 150])],
                'email' => [new Assert\NotBlank(), new Assert\Email()],
            ]
        );

        $input = $request->request->all();

        return Validation::createValidator()->validate($input, $constraint);
    }

    private function createCompany(Request $request): RedirectResponse
    {
        $command = new CreateCompanyCommand(
            $request->request->get('name'),
            $request->request->get('email')
        );

        $this->dispatch($command);

        //TODO: Generate a session
        //TODO: Translate messages

        return $this->redirectWithMessage(
            'dashboard',
            sprintf('Compañia %s creada', $command->name())
        );
    }
}
