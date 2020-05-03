<?php

namespace App\Infrastructure\Symfony\Controller\Landing\Register;

use App\Infrastructure\Symfony\Controller\LandingController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class RegisterGetController extends WebController implements LandingController
{
    public function view(): Response
    {
        return $this->render('pages/landing/register.html.twig');
    }
}
