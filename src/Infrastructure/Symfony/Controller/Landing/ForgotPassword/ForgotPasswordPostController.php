<?php

namespace App\Infrastructure\Symfony\Controller\Landing\ForgotPassword;

use App\Application\Company\Create\CreateCompanyDTO;
use App\Application\Company\Create\CreateCompanyService;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Event\CompanyCreatedEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ForgotPasswordPostController extends WebController
{
    public function view(Request $request, CreateCompanyService $service, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $validationErrors = $this->validateRequest($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('register_company_form', $validationErrors, $request)
            : $this->createCompany($request, $service, $dispatcher);
    }

    private function validateRequest(Request $request): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'namespace' => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type(['value '=> 'alnum'])],
                'name'      => [new Assert\NotBlank(), new Assert\Length(['max' => 150])],
                'email'     => [new Assert\NotBlank(), new Assert\Length(['max' => 150]), new Assert\Email()],
            ]
        );

        $input = $request->request->all();

        return Validation::createValidator()->validate($input, $constraint);
    }

    private function createCompany(Request $request, CreateCompanyService $service, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $command = new CreateCompanyDTO(
            $request->request->get('namespace'),
            $request->request->get('name'),
            $request->request->get('email')
        );

        $service->execute($command);

        $dispatcher->dispatch(new CompanyCreatedEvent($command->namespace()));

        //TODO: Generate a session
        //TODO: Translate messages

        return $this->redirectWithMessage(
            'dashboard',
            sprintf('Compañia %s creada', $command->name())
        );
    }
}