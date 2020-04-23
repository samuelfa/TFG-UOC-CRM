<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\DisplayList\FamiliarListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(FamiliarListService $service): Response
    {
        return $this->render('pages/crm/family/list.html.twig', [
            'familiars' => $service->__invoke()
        ]);
    }
}
