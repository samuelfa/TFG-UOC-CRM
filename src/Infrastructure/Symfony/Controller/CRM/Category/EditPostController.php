<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\Edit\EditCategoryDTO;
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
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_category_edit', ['id' => $id])
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token' => [new CSRF('category_edit')],
            'id'          => [new Assert\NotBlank(), new Assert\Type('int')],
            'name'        => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 50]), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $id = $request->request->get('id');
        $command = new EditCategoryDTO(
            $id,
            $request->request->get('name')
        );

        try {
            $this->dispatch($command);
        } catch (CategoryNotFound $exception){
            return $this->redirectWithError('The category has not been found', $request, 'crm_category_edit', ['id' => $id]);
        }

        return $this->redirectWithMessage('crm_category_list', 'Category edited');
    }
}
