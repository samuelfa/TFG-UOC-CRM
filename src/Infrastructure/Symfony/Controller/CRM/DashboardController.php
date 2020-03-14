<?php

namespace App\Infrastructure\Symfony\Controller\CRM;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/crm/dashboard.html.twig');
    }
}
