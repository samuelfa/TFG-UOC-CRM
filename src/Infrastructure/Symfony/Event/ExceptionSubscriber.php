<?php


namespace App\Infrastructure\Symfony\Event;


use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private TokenStorageInterface $tokenStorage;
    private RouterInterface $router;
    private string $domain;

    public function __construct(
        LoggerInterface $logger,
        TokenStorageInterface $tokenStorage,
        RouterInterface $router,
        string $domain
    )
    {
        $this->logger = $logger;
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
        $exception = $event->getThrowable();
        if(!($exception instanceof ResourceNotFoundException)){
            $this->logger->error($event->getThrowable()->getMessage());
            $this->logger->error($event->getThrowable());
        }

        $token = $this->tokenStorage->getToken();

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