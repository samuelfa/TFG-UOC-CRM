<?php


namespace App\Infrastructure\Symfony\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    private string $domain;
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router, string $domain)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->domain = $domain;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onEvent'
        ];
    }

    public function onEvent(ExceptionEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        //Diferenciar entre HTTP error y un error interno
        if($token && $token->getUser()){
            $url = $this->router->generate('crm_error');
        } else {
            $request = $event->getRequest();
            $scheme = $request->getScheme();
            $url = $this->router->generate('error');
            $url = "{$scheme}://{$this->domain}{$url}";
        }

        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
}