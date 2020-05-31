<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\Edit\EditFamiliarDTO;
use App\Domain\Familiar\FamiliarNotFound;
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
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_familiar_edit', ['nif' => $nif])
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('familiar_edit')],
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
        $nif = $request->request->get('nif');
        $command = new EditFamiliarDTO(
            $nif,
            $request->request->get('name'),
            $request->request->get('surname'),
            $request->request->get('birthday'),
            $request->request->get('portrait'),
            $request->request->get('customer')
        );

        try {
            $this->dispatch($command);
        } catch (FamiliarNotFound $exception){
            return $this->redirectWithError('familiar','The familiar has not been found', $request, 'crm_familiar_edit', ['nif' => $nif]);
        }

        return $this->redirectWithMessage('Familiar edited', 'crm_familiar_list');
    }
}
