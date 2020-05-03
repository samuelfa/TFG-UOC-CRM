<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\Delete\DeleteCategoryDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(int $id): RedirectResponse
    {
        $command = new DeleteCategoryDTO($id);
        $this->dispatch($command);

        return $this->redirectWithMessage('Category deleted', 'crm_category_list');
    }
}
