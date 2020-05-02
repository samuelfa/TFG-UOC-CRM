<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\DisplayList\CategoryListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(CategoryListService $service): Response
    {
        return $this->render('pages/crm/category/list.html.twig', [
            'categories' => $service->__invoke()
        ]);
    }
}
