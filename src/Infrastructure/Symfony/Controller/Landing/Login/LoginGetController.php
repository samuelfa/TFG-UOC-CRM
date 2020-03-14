<?php

namespace App\Infrastructure\Symfony\Controller\Landing\Login;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class LoginGetController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/landing/login.html.twig');
    }
}
