<?php


namespace App\Infrastructure\Symfony\Event\FamiliarSendEmail;

use Symfony\Component\Mime\Email as SymfonyEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailCreatedSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private string          $emailNoReply;

    public function __construct(MailerInterface $mailer, string $emailNoReply)
    {
        $this->mailer = $mailer;
        $this->emailNoReply = $emailNoReply;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailCreatedEvent::class => 'onEvent'
        ];
    }

    public function onEvent(EmailCreatedEvent $event): void
    {
        $email = $event->email();
        $recipients = $email->recipients();
        $recipients = array_map(static function($value){
            return (string) $value;
        }, $recipients);

        $symfonyEmail = new SymfonyEmail();
        $symfonyEmail
            ->to(...$recipients)
            ->from($this->emailNoReply)
            ->subject($email->subject())
            ->text($email->body())
        ;

        try {
            $this->mailer->send($symfonyEmail);
        } catch (TransportExceptionInterface $e) {
        }
    }
}