<?php


namespace App\Infrastructure\Symfony\Subscriber;


use App\Domain\Factory\ConnectionFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CompanyDomainSubscriber implements EventSubscriberInterface
{
    private string $domain;
    private ConnectionFactory $connectionFactory;

    public function __construct(string $domain, ConnectionFactory $connectionFactory)
    {
        $this->domain = $domain;
        $this->connectionFactory = $connectionFactory;
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

    private function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $host = $request->getHost();
        $namespace = str_replace($host, '', $this->domain);

        $this->connectionFactory->preloadSettings($namespace);
    }
}