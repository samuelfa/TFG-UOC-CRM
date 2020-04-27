<?php

namespace App\Infrastructure\Symfony\Controller\CRM\Familiar;

use App\Application\Familiar\DisplayList\FamiliarListService;
use App\Domain\User\User;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\Response;

class ListGetController extends WebController
{
    public function view(FamiliarListService $service): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('pages/crm/familiar/list.html.twig', [
            'familiars' => $service->__invoke($user)
        ]);
    }
}
