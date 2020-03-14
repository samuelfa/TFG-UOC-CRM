<?php


namespace App\Infrastructure\Symfony\Event;


use App\Domain\Factory\ConnectionFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CustomerNamespaceSubscriber implements EventSubscriberInterface
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
            KernelEvents::CONTROLLER => 'onEvent'
        ];
    }

    public function onEvent(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $host = $request->getHost();
        $namespace = $this->calculateNamespace($host);

        $this->connectionFactory->preloadSettings($namespace);
    }

    private function calculateNamespace(string $host): string
    {
        $namespace = str_replace($this->domain, '', $host);
        if(empty($namespace)){
            return '';
        }

        $lastCharacter = $namespace[strlen($namespace) - 1];
        if($lastCharacter === '.'){
            return substr($namespace, 1);
        }

        return $namespace;
    }
}