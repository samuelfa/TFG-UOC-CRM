<?php


namespace App\Infrastructure\Symfony\Event\ForgotPassword;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotPasswordEmailCreatedSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ForgotPasswordEmailCreatedEvent::class => 'onEvent'
        ];
    }

    public function onEvent(ForgotPasswordEmailCreatedEvent $event): void
    {
        $token = $event->token();

        $email = new TemplatedEmail();
        $email
            ->to($token->emailAddress()->value())
            ->from('no-reply@crm.localhost')
            ->subject($this->translator->trans('CRM: You request a new password!!'))
            ->htmlTemplate('emails/forgot-password.html.twig')
            ->textTemplate('emails/forgot-password.txt.twig')
            ->context([
                'token' => $token
            ])
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }
}