<?php

namespace App\Infrastructure\Symfony\Controller;

use App\Application\DTO;
use App\Infrastructure\Symfony\Event\TransactionalServiceHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class WebController extends AbstractController
{
    private TranslatorInterface $translator;
    private TransactionalServiceHandler $transactionalServiceHandler;
    private ValidatorInterface $validator;

    public function __construct(
        TranslatorInterface $translator,
        TransactionalServiceHandler $transactionalServiceHandler,
        ValidatorInterface $validator
    ) {
        $this->translator = $translator;
        $this->transactionalServiceHandler = $transactionalServiceHandler;
        $this->validator = $validator;
    }

    protected function redirectWithMessage(string $message, string $routeName, array $routeParameters = []): RedirectResponse
    {
        $message = $this->translator->trans($message);
        $this->addFlash('message', $message);

        return $this->redirectToRoute($routeName, $routeParameters);
    }

    protected function dispatch(DTO $dto): DTO
    {
        return $this->transactionalServiceHandler->dispatch($dto);
    }

    protected function redirectWithErrors(
        ConstraintViolationListInterface $errors,
        Request $request,
        string $routeName,
        array $parameters = []
    ): RedirectResponse {
        $this->addFlashFor('error', $this->formatFlashErrors($errors));
        $this->addFlashFor('input', $request->request->all());

        return $this->redirectToRoute($routeName, $parameters);
    }

    protected function redirectWithError(
        string $postfix,
        string $error,
        Request $request,
        string $routeName,
        array $parameters = []
    ): RedirectResponse {
        $error = $this->translator->trans($error);
        $this->addFlash("error.{$postfix}", $error);
        $this->addFlashFor('input', $request->request->all());

        return $this->redirectToRoute($routeName, $parameters);
    }

    protected function validateRequest(Request $request, array $assertions): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection($assertions);
        $input = $request->request->all();
        return $this->validator->validate($input, $constraint);
    }

    private function formatFlashErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[str_replace(['[', ']'], ['', ''], $violation->getPropertyPath())] = $this->translator->trans($violation->getMessage());
        }

        return $errors;
    }

    private function addFlashFor(string $prefix, array $messages): void
    {
        foreach ($messages as $key => $message) {
            $this->addFlash("{$prefix}.{$key}", $message);
        }
    }
}
