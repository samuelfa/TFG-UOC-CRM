<?php


namespace App\Infrastructure\Symfony\Event;


use App\Domain\Company\NamespacesCalculator;
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
        $controllerName = get_class($controllerClass);
        if(!$this->isApplicationController($controllerName)){
            return;
        }

        $request = $event->getRequest();
        $host = $request->getHost();
        $namespace = $this->namespacesCalculator->obtain($host);
        if(empty($namespace)){
            if(!$this->isLanding($controllerName)){
                $this->redirectToLandingHome($event);
            }
            return;
        }

        if($this->isLanding($controllerName)){
            $this->redirectToLandingHome($event);
            return;
        }

        $token = $this->tokenStorage->getToken();
        if($token && !($token instanceof AnonymousToken) && $token->isAuthenticated()){
            if($this->isAnonymousController($controllerName)){
                return;
            }
            return;
        }

        if($this->isAnonymousController($controllerName)){
            return;
        }

        $this->redirectToCRMLogin($event);
    }

    private function isLanding(string $path): bool
    {
        return strpos($path, 'Symfony\Controller\Landing') !== false;
    }

    private function isAnonymousController(string $path): bool
    {
        return
            strpos($path, 'Symfony\Controller\CRM\Login') !== false ||
            strpos($path, 'Symfony\Controller\CRM\ForgotPassword') !== false
        ;
    }

    private function isApplicationController(string $path): bool
    {
        return strpos($path, 'App\Infrastructure\Symfony\Controller') !== false;
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