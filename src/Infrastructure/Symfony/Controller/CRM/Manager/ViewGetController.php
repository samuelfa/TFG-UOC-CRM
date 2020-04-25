<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Manager;

use App\Application\Manager\DisplayList\ManagerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ViewGetController extends WebController
{
    public function view(ManagerListService $service): Response
    {
        //TODO
        return new Response();
    }
}
