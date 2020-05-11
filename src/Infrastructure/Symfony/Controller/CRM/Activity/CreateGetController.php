<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Category\DisplayList\CategoryListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateGetController extends WebController
{
    public function view(CategoryListService $service, Request $request): Response
    {
        $categories = $service->__invoke();
        if(empty($categories)){
            return $this->redirectWithError('Please, create some category first', $request, 'crm_activity_list');
        }

        return $this->render('pages/crm/activity/create.html.twig', [
            'categories' => $categories
        ]);
    }
}
