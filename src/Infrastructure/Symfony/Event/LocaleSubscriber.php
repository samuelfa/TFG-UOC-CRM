<?php


namespace App\Infrastructure\Symfony\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    /** @var string[] */
    private array  $availableLanguages;

    public function __construct(array $availableLanguages)
    {
        $this->availableLanguages = $availableLanguages;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $defaultLocale = $request->getPreferredLanguage($this->availableLanguages);
            $request->setLocale($request->getSession()->get('_locale', $defaultLocale));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}