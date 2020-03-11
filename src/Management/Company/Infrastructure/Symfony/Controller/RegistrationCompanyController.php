<?php

namespace App\Management\Company\Infrastructure\Symfony\Controller;

use App\Shared\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationCompanyController extends WebController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('pages/company/register_form.html.twig');
    }
}
