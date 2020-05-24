<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Calendar;

use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class CalendarGetController extends WebController
{
    public function view(): Response
    {
        return $this->render('pages/crm/calendar.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
