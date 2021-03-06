<?php

namespace App\Infrastructure\Symfony\Controller\Landing;

use App\Infrastructure\Symfony\Controller\LandingController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends WebController implements LandingController
{
    public function view(): Response
    {
        return $this->render('pages/landing/home.html.twig');
    }
}
