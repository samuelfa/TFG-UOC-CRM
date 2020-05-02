<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\Delete\DeleteCategoryDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeleteGetController extends WebController
{
    public function view(string $name): RedirectResponse
    {
        $command = new DeleteCategoryDTO($name);
        $this->dispatch($command);

        return $this->redirectWithMessage('crm_category_list', 'Category deleted');
    }
}
