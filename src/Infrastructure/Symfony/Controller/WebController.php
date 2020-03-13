<?php

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Twig\Environment;

abstract class WebController
{
    private Environment      $twig;
    private RouterInterface  $router;
    private SessionInterface $session;

    public function __construct(
        Environment $twig,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->session = $session;
    }

    public function render(string $templatePath, array $arguments = []): Response
    {
        return new Response($this->twig->render($templatePath, $arguments));
    }

    public function redirect(string $routeName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName), 302);
    }

    public function redirectWithMessage(string $routeName, string $message): RedirectResponse
    {
        $this->addFlashFor('message', [$message]);

        return $this->redirect($routeName);
    }

    public function redirectWithErrors(
        string $routeName,
        ConstraintViolationListInterface $errors,
        Request $request
    ): RedirectResponse {
        $this->addFlashFor('errors', $this->formatFlashErrors($errors));
        $this->addFlashFor('inputs', $request->request->all());

        return new RedirectResponse($this->router->generate($routeName), 302);
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
