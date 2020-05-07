<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar\Action;

use App\Application\Familiar\Action\LinkActivity\CalendarEventsDTO;
use App\Application\Familiar\Action\LinkActivity\CalendarEventsService;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CalendarEventsGetController extends WebController
{
    public function view(
        string $nif,
        CalendarEventsService $service,
        Request $request
    ): JsonResponse
    {
        $dto = new CalendarEventsDTO($nif, $request->query->get('start'), $request->query->get('end'));
        return new JsonResponse($service->__invoke($dto));
    }
}
