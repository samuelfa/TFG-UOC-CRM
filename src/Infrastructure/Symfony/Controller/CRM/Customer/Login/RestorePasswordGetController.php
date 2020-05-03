<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer\Login;

use App\Application\Login\TokenForgotPasswordService;
use App\Infrastructure\Symfony\Controller\AnonymousController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class RestorePasswordGetController extends WebController implements AnonymousController
{
    public function view(string $token, TokenForgotPasswordService $service): Response
    {
        $object = $service->__invoke($token);
        return $this->render('pages/crm/customer/restore-password.html.twig', [
            'token' => $object
        ]);
    }
}
