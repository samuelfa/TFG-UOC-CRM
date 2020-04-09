<?php

namespace App\Infrastructure\Symfony\Controller\Landing\SigIn;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SigInGetController extends WebController
{
    public function view(AuthenticationUtils $authenticationUtils): Response
    {
        // get the sig in error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('pages/landing/sig_in.html.twig', ['error' => $error]);
    }
}
