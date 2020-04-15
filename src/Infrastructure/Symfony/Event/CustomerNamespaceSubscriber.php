<?php


namespace App\Infrastructure\Symfony\Event;


use App\Domain\Company\NamespacesCalculator;
use App\Domain\Factory\ConnectionFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CustomerNamespaceSubscriber implements EventSubscriberInterface
{
    private ConnectionFactory $connectionFactory;
    private NamespacesCalculator $namespacesCalculator;

    public function __construct(
        NamespacesCalculator $namespacesCalculator,
        ConnectionFactory $connectionFactory
    )
    {
        $this->connectionFactory = $connectionFactory;
        $this->namespacesCalculator = $namespacesCalculator;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $host = $request->getHost();
        $namespace = $this->namespacesCalculator->obtain($host);

        $this->connectionFactory->preloadSettings($namespace);
    }
}