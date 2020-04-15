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

    protected function redirectWithMessage(string $routeName, string $message, ...$parameters): RedirectResponse
    {
        $message = $this->translator->trans($message, $parameters);
        $this->addFlash('message', $message);

        return $this->redirectToRoute($routeName);
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
        $this->addFlashFor('error', $this->formatFlashErrors($errors));
        $this->addFlashFor('inputs', $request->request->all());

        return $this->redirectToRoute($routeName);
    }

    protected function redirectWithError(
        string $routeName,
        string $error,
        Request $request
    ): RedirectResponse {
        $this->addFlash('error', $error);
        $this->addFlashFor('inputs', $request->request->all());

        return $this->redirectToRoute($routeName);
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
            $errors[str_replace(['[', ']'], ['', ''], $violation->getPropertyPath())] = $violation->getMessage();
        }

        return $errors;
    }

    private function addFlashFor(string $prefix, array $messages): void
    {
        foreach ($messages as $key => $message) {
           $this->addFlash($prefix, $message);
        }
    }
}
