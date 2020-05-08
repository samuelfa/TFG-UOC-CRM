<?php


namespace App\Infrastructure\Symfony\Controller\Landing;


use App\Infrastructure\Symfony\Controller\LandingController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends WebController implements LandingController
{
    public function view(string $locale, Request $request): RedirectResponse
    {
        $locales = ['es', 'en'];
        if(!in_array($locale, $locales, true)){
            $locale = 'en';
        }

        $request->setLocale($locale);
        $request->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        if(empty($referer)){
            return $this->redirectToRoute('index');
        }

        return $this->redirect($referer);
    }
}