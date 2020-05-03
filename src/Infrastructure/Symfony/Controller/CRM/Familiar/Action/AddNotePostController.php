<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar\Action;

use App\Application\Familiar\Action\AddNote\AddNoteDTO;
use App\Domain\Familiar\FamiliarNotFound;
use App\Domain\User\User;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use App\Infrastructure\Symfony\Validator\Constraints\NIF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AddNotePostController extends WebController
{
    public function view(string $nif, Request $request): RedirectResponse
    {
        $request->request->set('nif', $nif);
        if(!$request->request->has('private')){
            $request->request->set('private', 0);
        }
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_familiar_send_email', ['nif' => $nif])
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('add-note')],
            'nif'           => [new NIF()],
            'message'       => [new Assert\NotBlank(), new Assert\Type('string')],
            'private'       => [new Assert\Type('string'), new Assert\Choice(['1', '0'])],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $nif = $request->request->get('nif');
        $command = new AddNoteDTO(
            $nif,
            $request->request->get('message'),
            $request->request->getBoolean('private'),
            $user
        );

        try {
            $this->dispatch($command);
        } catch (FamiliarNotFound $exception){
            return $this->redirectWithError('The familiar has not been found', $request, 'crm_familiar_send_email', ['nif' => $nif]);
        }

        return $this->redirectWithMessage('crm_familiar_list', 'Familiar edited');
    }
}
