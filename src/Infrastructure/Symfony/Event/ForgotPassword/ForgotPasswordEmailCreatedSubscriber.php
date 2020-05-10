<?php


namespace App\Infrastructure\Symfony\Event\ForgotPassword;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotPasswordEmailCreatedSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private LoggerInterface $logger;

    public function __construct(
        MailerInterface $mailer,
        TranslatorInterface $translator,
        LoggerInterface $logger
    )
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->logger = $logger;
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
            ->context([
                'token' => $token
            ])
        ;

        if($event->isCustomer()){
            $email
                ->htmlTemplate('emails/customer/forgot-password.html.twig')
                ->textTemplate('emails/customer/forgot-password.txt.twig')
            ;
        } else {
            $email
                ->htmlTemplate('emails/employee/forgot-password.html.twig')
                ->textTemplate('emails/employee/forgot-password.txt.twig')
            ;
        }

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception);
        }
    }
}