<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Activity;

use App\Application\Activity\View\ActivityViewService;
use App\Application\Activity\View\ViewActivityDTO;
use App\Application\Category\DisplayList\CategoryListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class EditGetController extends WebController
{
    public function view(int $id, ActivityViewService $service, CategoryListService $categoryListService): Response
    {
        $categories = $categoryListService->__invoke();
        $dto = new ViewActivityDTO($id);
        return $this->render('pages/crm/activity/edit.html.twig', [
            'activity' => $service->__invoke($dto),
            'categories' => $categories
        ]);
    }
}
