<?php

namespace App\Infrastructure\Symfony\Controller\Landing\ForgotPassword;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordGetController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/landing/forgot-password.twig');
    }
}
