<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Calendar;

use App\Application\Calendar\CalendarEventsDTO;
use App\Application\Calendar\CalendarEventsService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CalendarEventsGetController extends WebController
{
    public function view(
        CalendarEventsService $service,
        Request $request
    ): JsonResponse
    {
        $dto = new CalendarEventsDTO($request->query->get('start'), $request->query->get('end'));
        return new JsonResponse($service->__invoke($dto));
    }
}
