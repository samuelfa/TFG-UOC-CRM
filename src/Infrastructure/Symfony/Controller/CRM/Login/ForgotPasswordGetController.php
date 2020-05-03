<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Login;

use App\Infrastructure\Symfony\Controller\AnonymousController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordGetController extends WebController implements AnonymousController
{
    public function view(): Response
    {
        return $this->render('pages/crm/login/forgot-password.twig');
    }
}
