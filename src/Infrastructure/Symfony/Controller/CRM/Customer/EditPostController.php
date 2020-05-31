<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer;

use App\Application\Customer\Edit\EditCustomerDTO;
use App\Domain\AlreadyExistsEmailAddress;
use App\Domain\Customer\CustomerNotFound;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use App\Infrastructure\Symfony\Validator\Constraints\NIF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Constraints as Assert;

class EditPostController extends WebController
{
    public function view(string $nif, Request $request): RedirectResponse
    {
        $request->request->set('nif', $nif);
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_customer_edit', ['nif' => $nif])
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('customer_edit')],
            'nif'           => [new NIF()],
            'email_address' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 150]), new Assert\Email()],
            'name'          => [new Assert\NotBlank(), new Assert\Length(['min' => 1, 'max' => 150]), new Assert\Type('string')],
            'surname'       => [new Assert\Length(['min' => 0, 'max' => 150]), new Assert\Type('string')],
            'birthday'      => [new Assert\Date()],
            'portrait'      => [new Assert\Length(['min' => 0, 'max' => 300]), new Assert\Type('string')],
            'password'      => [new Assert\Length(['min' => 0, 'max' => 300]), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $nif = $request->request->get('nif');
        $command = new EditCustomerDTO(
            $nif,
            $request->request->get('email_address'),
            $request->request->get('name'),
            $request->request->get('surname'),
            $request->request->get('birthday'),
            $request->request->get('portrait'),
            $request->request->get('password'),
        );

        try {
            $this->dispatch($command);
        } catch (CustomerNotFound $exception){
            return $this->redirectWithError('customer','The customer has not been found', $request, 'crm_customer_edit', ['nif' => $nif]);
        } catch (AlreadyExistsEmailAddress $exception){
            return $this->redirectWithError('email_address','The email address is already in use', $request, 'crm_customer_edit', ['nif' => $nif]);
        }

        return $this->redirectWithMessage('Customer edited', 'crm_customer_list');
    }
}
