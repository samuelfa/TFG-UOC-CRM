<?php


namespace App\Infrastructure\Symfony\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    /**
     * @var string
     */
    private string $domain;

    public function __construct(RouterInterface $router, string $domain)
    {
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
        $request = $event->getRequest();
        $scheme = $request->getScheme();
        $url = $this->router->generate('error');
        $response = new RedirectResponse("{$scheme}://{$this->domain}{$url}");
        $event->setResponse($response);
    }
}