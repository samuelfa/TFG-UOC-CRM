<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Category;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class CreateGetController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/crm/category/create.html.twig');
    }
}
