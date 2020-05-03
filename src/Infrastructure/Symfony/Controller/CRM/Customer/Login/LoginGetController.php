<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Customer\Login;

use App\Infrastructure\Symfony\Controller\AnonymousController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginGetController extends WebController implements AnonymousController
{
    public function view(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/crm/customer/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
