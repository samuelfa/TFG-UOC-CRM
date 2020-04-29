<?php

namespace App\Infrastructure\Symfony\Controller\CRM;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/crm/error.html.twig');
    }
}
