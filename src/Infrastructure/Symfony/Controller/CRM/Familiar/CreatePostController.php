<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\Create\CreateFamiliarDTO;
use App\Domain\AlreadyExistsNif;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use App\Infrastructure\Symfony\Validator\Constraints\NIF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CreatePostController extends WebController
{
    public function view(Request $request): RedirectResponse
    {
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_familiar_create')
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('familiar_create')],
            'nif'           => [new NIF()],
            'name'          => [new Assert\NotBlank(), new Assert\Length(['min' => 1, 'max' => 150]), new Assert\Type('string')],
            'surname'       => [new Assert\Length(['min' => 0, 'max' => 150]), new Assert\Type('string')],
            'birthday'      => [new Assert\Date()],
            'portrait'      => [new Assert\Length(['min' => 0, 'max' => 300]), new Assert\Type('string')],
            'customer'      => [new NIF()],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $command = new CreateFamiliarDTO(
            $request->request->get('nif'),
            $request->request->get('name'),
            $request->request->get('surname'),
            $request->request->get('birthday'),
            $request->request->get('portrait'),
            $request->request->get('customer')
        );

        try {
            $this->dispatch($command);
        } catch (AlreadyExistsNif $exception){
            return $this->redirectWithError('nif','The nif is already in use', $request, 'crm_familiar_create');
        }

        return $this->redirectWithMessage('Familiar created', 'crm_familiar_list');
    }
}
