<?php

namespace App\Infrastructure\Symfony\Controller\Landing\Register;

use App\Application\Company\Create\CreateCompanyDTO;
use App\Application\Company\Create\CreateCompanyService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class RegisterPostController extends WebController
{
    public function view(Request $request, CreateCompanyService $service): RedirectResponse
    {
        $validationErrors = $this->validateRequest($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('register', $validationErrors, $request)
            : $this->createCompany($request, $service);
    }

    private function validateRequest(Request $request): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'namespace' => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type('alnum')],
                'name'      => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Type('string')],
                'email_address'     => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Email()],
                'password'  => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type('string')],
            ]
        );

        $input = $request->request->all();

        return Validation::createValidator()->validate($input, $constraint);
    }

    private function createCompany(Request $request, CreateCompanyService $service): RedirectResponse
    {
        $command = new CreateCompanyDTO(
            $request->request->get('namespace'),
            $request->request->get('name'),
            $request->request->get('email_address'),
            $request->request->get('password')
        );

        $service->execute($command);

        //TODO: Generate a session

        return $this->redirectWithMessage(
            'dashboard',
            'Compañia %s creada',
            $command->name()
        );
    }
}
