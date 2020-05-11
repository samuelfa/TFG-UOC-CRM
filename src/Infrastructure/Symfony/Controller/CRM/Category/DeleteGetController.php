<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\Delete\DeleteCategoryDTO;
use App\Domain\Category\CategoryLinkedWithActivities;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteGetController extends WebController
{
    public function view(int $id, Request $request): RedirectResponse
    {
        $command = new DeleteCategoryDTO($id);

        try {
            $this->dispatch($command);
        } catch (CategoryLinkedWithActivities $exception){
            return $this->redirectWithError('Category linked with some activities', $request, 'crm_category_list');
        }

        return $this->redirectWithMessage('Category deleted', 'crm_category_list');
    }
}
