<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Category\DisplayList\CategoryListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class CreateGetController extends WebController
{
    public function view(CategoryListService $service): Response
    {
        $categories = $service->__invoke();

        return $this->render('pages/crm/activity/create.html.twig', [
            'categories' => $categories
        ]);
    }
}
