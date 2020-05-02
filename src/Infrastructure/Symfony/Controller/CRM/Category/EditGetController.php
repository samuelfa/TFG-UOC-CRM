<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\View\CategoryViewService;
use App\Application\Category\View\ViewCategoryDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class EditGetController extends WebController
{
    public function view(int $id, CategoryViewService $service): Response
    {
        $dto = new ViewCategoryDTO($id);
        $category = $service->__invoke($dto);
        return $this->render('pages/crm/category/edit.html.twig', [
            'category' => $category
        ]);
    }
}
