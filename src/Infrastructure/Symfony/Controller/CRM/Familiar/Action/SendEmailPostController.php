<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar\Action;

use App\Application\Familiar\Action\SendEmail\SendEmailDTO;
use App\Domain\Familiar\FamiliarNotFound;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use App\Infrastructure\Symfony\Validator\Constraints\NIF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SendEmailPostController extends WebController
{
    public function view(string $nif, Request $request): RedirectResponse
    {
        $request->request->set('nif', $nif);
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_familiar_send_email', ['nif' => $nif])
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token'   => [new CSRF('familiar-send-email')],
            'nif'           => [new NIF()],
            'subject'       => [new Assert\NotBlank(), new Assert\Length(['min' => 1, 'max' => 150]),  new Assert\Type('string')],
            'body'          => [new Assert\NotBlank(), new Assert\Type('string')],
            'recipients'    => [new Assert\NotBlank(), new Assert\Type('array'), new Assert\Count(['min' => 1]), new Assert\All([new Assert\NotBlank(), new Assert\Email()])],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $nif = $request->request->get('nif');
        $command = new SendEmailDTO(
            $nif,
            $request->request->get('subject'),
            $request->request->get('body'),
            $request->request->get('recipients'),
        );

        try {
            $this->dispatch($command);
        } catch (FamiliarNotFound $exception){
            return $this->redirectWithError('The familiar has not been found', $request, 'crm_familiar_send_email', ['nif' => $nif]);
        }

        return $this->redirectWithMessage('Email sent', 'crm_familiar_view', ['nif' => $nif]);
    }
}
