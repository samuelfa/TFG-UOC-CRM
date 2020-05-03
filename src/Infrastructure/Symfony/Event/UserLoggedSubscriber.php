<?php


namespace App\Infrastructure\Symfony\Event;


use App\Domain\Company\NamespacesCalculator;
use App\Infrastructure\Symfony\Controller\AnonymousController;
use App\Infrastructure\Symfony\Controller\LandingController;
use App\Infrastructure\Symfony\Controller\WebController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserLoggedSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;
    /**
     * @var NamespacesCalculator
     */
    private NamespacesCalculator $namespacesCalculator;
    /**
     * @var string
     */
    private string $domain;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        NamespacesCalculator $namespacesCalculator,
        string $domain,
        RouterInterface $router
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->namespacesCalculator = $namespacesCalculator;
        $this->domain = $domain;
        $this->router = $router;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController'
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if(!is_array($controller)){
            return;
        }

        [$controllerClass] = $controller;
        if(!$this->isApplicationController($controllerClass)){
            return;
        }

        $request = $event->getRequest();
        $host = $request->getHost();
        $namespace = $this->namespacesCalculator->obtain($host);
        if(empty($namespace)){
            if(!$this->isLanding($controllerClass)){
                $this->redirectToLandingHome($event);
            }
            return;
        }

        if($this->isLanding($controllerClass)){
            $this->redirectToLandingHome($event);
            return;
        }

        $token = $this->tokenStorage->getToken();
        if($token && !($token instanceof AnonymousToken) && $token->isAuthenticated()){
            if($this->isAnonymousController($controllerClass)){
                return;
            }
            return;
        }

        if($this->isAnonymousController($controllerClass)){
            return;
        }

        $this->redirectToCRMLogin($event);
    }

    private function isLanding(object $object): bool
    {
        return $object instanceof LandingController;
    }

    private function isAnonymousController(object $object): bool
    {
        return $object instanceof AnonymousController;
    }

    private function isApplicationController(object $object): bool
    {
        return $object instanceof WebController;
    }

    private function redirectToLandingHome(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $scheme = $request->getScheme();
        $domain = $this->domain;
        $event->setController(static function() use ($scheme, $domain) {
            return new RedirectResponse("{$scheme}://{$domain}");
        });
    }

    private function redirectToCRMLogin(ControllerEvent $event): void
    {
        $path = $this->router->generate('crm_login');
        $event->setController(static function() use ($path) {
            return new RedirectResponse($path);
        });
    }

}