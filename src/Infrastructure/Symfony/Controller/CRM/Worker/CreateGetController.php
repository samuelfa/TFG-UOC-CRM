<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Worker;

use App\Application\Manager\DisplayList\ManagerListService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class CreateGetController extends WebController
{
    public function view(ManagerListService $service): Response
    {
        //TODO
    }
}
