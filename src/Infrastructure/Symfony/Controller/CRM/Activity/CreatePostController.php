<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\Create\CreateActivityDTO;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
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
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_activity_create')
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token' => [new CSRF('activity_create')],
            'name'        => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 50]), new Assert\Type('string')],
            'start_at'    => [new Assert\NotBlank(), new Assert\Date()],
            'finish_at'   => [new Assert\NotBlank(), new Assert\Date(), new Assert\Expression([
                'expression' => 'start_at <= finish_at',
                'values' => ['start_at' => $request->request->get('start_at'), 'finish_at' => $request->request->get('finish_at')],
            ])],
            'category'    => [new Assert\NotBlank(), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $command = new CreateActivityDTO(
            $request->request->get('name'),
            $request->request->get('start_at'),
            $request->request->get('finish_at'),
            $request->request->getInt('category'),
        );

        try {
            $this->dispatch($command);
        } catch (CategoryNotFound $exception){
            return $this->redirectWithError('The category does not exist', $request, 'crm_activity_create');
        }

        return $this->redirectWithMessage('crm_activity_list', 'Activity created');
    }
}
