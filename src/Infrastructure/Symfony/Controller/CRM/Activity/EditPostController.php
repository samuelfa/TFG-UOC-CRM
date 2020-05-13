<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\Edit\EditActivityDTO;
use App\Domain\Activity\ActivityNotFound;
use App\Domain\Category\CategoryNotFound;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Constraints as Assert;

class EditPostController extends WebController
{
    public function view(int $id, Request $request): RedirectResponse
    {
        $request->request->set('id', $id);
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_activity_edit', ['id' => $id])
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token' => [new CSRF('activity_edit')],
            'id'          => [new Assert\NotBlank(), new Assert\Type('int'), new Assert\Positive()],
            'name'        => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 50]), new Assert\Type('string')],
            'start_at'    => [new Assert\NotBlank(), new Assert\DateTime(['format' => 'Y-m-d\TH:i'])],
            'finish_at'   => [new Assert\NotBlank(), new Assert\DateTime(['format' => 'Y-m-d\TH:i']), new Assert\Expression([
                'expression' => 'start_at <= finish_at',
                'values' => ['start_at' => $request->request->get('start_at'), 'finish_at' => $request->request->get('finish_at')],
            ])],
            'category'    => [new Assert\NotBlank(), new Assert\Type('digit')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $id = $request->request->getInt('id');
        $command = new EditActivityDTO(
            $id,
            $request->request->get('name'),
            $request->request->get('start_at'),
            $request->request->get('finish_at'),
            $request->request->getInt('category'),
        );

        try {
            $this->dispatch($command);
        } catch (ActivityNotFound $exception){
            return $this->redirectWithError('The activity has not been found', $request, 'crm_activity_edit', ['id' => $id]);
        } catch (CategoryNotFound $exception){
            return $this->redirectWithError('The category does not exist', $request, 'crm_activity_edit', ['id' => $id]);
        }

        return $this->redirectWithMessage('Activity edited', 'crm_activity_list');
    }
}
