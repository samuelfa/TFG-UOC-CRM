<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer;

use App\Application\Customer\Create\CreateCustomerDTO;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\AlreadyExistsNif;
use App\Domain\PasswordRandomGenerator;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use App\Infrastructure\Symfony\Validator\Constraints\NIF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CreatePostController extends WebController
{
    public function view(Request $request, PasswordRandomGenerator $passwordRandomGenerator): RedirectResponse
    {
        $request->request->set('password', $passwordRandomGenerator->generate());

        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_customer_create')
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('customer_create')],
            'nif'           => [new NIF()],
            'email_address' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 150]), new Assert\Email()],
            'name'          => [new Assert\NotBlank(), new Assert\Length(['min' => 1, 'max' => 150]), new Assert\Type('string')],
            'surname'       => [new Assert\Length(['min' => 0, 'max' => 150]), new Assert\Type('string')],
            'birthday'      => [new Assert\Date()],
            'portrait'      => [new Assert\Length(['min' => 0, 'max' => 300]), new Assert\Type('string')],
            'password'      => [new Assert\NotBlank(), new Assert\Length(['min' => 4, 'max' => 50]), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $command = new CreateCustomerDTO(
            $request->request->get('nif'),
            $request->request->get('email_address'),
            $request->request->get('name'),
            $request->request->get('surname'),
            $request->request->get('birthday'),
            $request->request->get('portrait'),
            $request->request->get('password'),
        );

        try {
            $this->dispatch($command);
        } catch (AlreadyExistsNif $exception){
            return $this->redirectWithError('nif','The nif is already in use', $request, 'crm_customer_create');
        } catch (AlreadyExistsEmailAddress $exception){
            return $this->redirectWithError('email_address','The email address is already in use', $request, 'crm_customer_create');
        }

        return $this->redirectWithMessage('Customer created', 'crm_customer_list');
    }
}
