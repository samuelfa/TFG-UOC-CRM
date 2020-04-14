<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Worker;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/crm/dashboard.html.twig');
    }
}
