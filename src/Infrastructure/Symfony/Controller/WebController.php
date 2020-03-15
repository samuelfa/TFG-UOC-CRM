<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Application\DTO;
use App\Infrastructure\Symfony\Event\TransactionalServiceHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class WebController
{
    private Environment      $twig;
    private RouterInterface  $router;
    private SessionInterface $session;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;
    /**
     * @var TransactionalServiceHandler
     */
    private TransactionalServiceHandler $transactionalServiceHandler;

    public function __construct(
        Environment $twig,
        RouterInterface $router,
        SessionInterface $session,
        TranslatorInterface $translator,
        TransactionalServiceHandler $transactionalServiceHandler
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
        $this->transactionalServiceHandler = $transactionalServiceHandler;
    }

    protected function render(string $templatePath, array $arguments = []): Response
    {
        return new Response($this->twig->render($templatePath, $arguments));
    }

    protected function redirect(string $routeName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName), 302);
    }

    protected function redirectWithMessage(string $routeName, string $message, ...$parameters): RedirectResponse
    {
        $message = $this->translator->trans($message, $parameters);
        $this->addFlashFor('message', [$message]);

        return $this->redirect($routeName);
    }

    protected function dispatch(DTO $dto): DTO
    {
        return $this->transactionalServiceHandler->dispatch($dto);
    }

    protected function redirectWithErrors(
        string $routeName,
        ConstraintViolationListInterface $errors,
        Request $request
    ): RedirectResponse {
        $this->addFlashFor('errors', $this->formatFlashErrors($errors));
        $this->addFlashFor('inputs', $request->request->all());

        return new RedirectResponse($this->router->generate($routeName), 302);
    }

    protected function validateRequest(Request $request, array $assertions): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection($assertions);
        $input = $request->request->all();
        return Validation::createValidator()->validate($input, $constraint);
    }

    private function formatFlashErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[str_replace(['[', ']'], ['', ''], $violation->getPropertyPath())] = $violation->getMessage();
        }

        return $errors;
    }

    private function addFlashFor(string $prefix, array $messages): void
    {
        foreach ($messages as $key => $message) {
            /** @var FlashBagInterface $bag */
            $bag = $this->session->getFlashBag();
            $bag->set($prefix.'.'.$key, $message);
        }
    }
}
