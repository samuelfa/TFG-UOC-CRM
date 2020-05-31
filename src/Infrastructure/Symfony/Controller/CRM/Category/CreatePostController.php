<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\Create\CreateCategoryDTO;
use App\Domain\Category\AlreadyExistsCategory;
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
            ? $this->redirectWithErrors($validationErrors, $request, 'crm_category_create')
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token' => [new CSRF('category_create')],
            'name'        => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 50]), new Assert\Type('string')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $command = new CreateCategoryDTO($request->request->get('name'));

        try {
            $this->dispatch($command);
        } catch (AlreadyExistsCategory $exception){
            return $this->redirectWithError('name','The category is already in use', $request, 'crm_category_create');
        }

        return $this->redirectWithMessage('Category created', 'crm_category_list');
    }
}
