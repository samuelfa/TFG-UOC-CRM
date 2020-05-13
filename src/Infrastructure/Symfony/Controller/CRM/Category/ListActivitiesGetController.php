<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Application\Category\DisplayList\ActivityListService;
use App\Application\Category\View\CategoryViewService;
use App\Application\Category\View\ViewCategoryDTO;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListActivitiesGetController extends WebController
{
    public function view(int $id, CategoryViewService $categoryViewService, ActivityListService $service): Response
    {
        $dto = new ViewCategoryDTO($id);
        $category = $categoryViewService->__invoke($dto);
        return $this->render('pages/crm/category/list-activities.html.twig', [
            'category' => $category,
            'activities' => $service->__invoke($category)
        ]);
    }
}
