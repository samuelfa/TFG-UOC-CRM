<?php

namespace App\Infrastructure\Symfony\Controller\Landing\SignIn;

use App\Application\Company\SignIn\SignInNamespaceDTO;
use App\Domain\Company\CompanyNotFound;
use App\Infrastructure\Symfony\Controller\WebController;
use App\Infrastructure\Symfony\Validator\Constraints\CSRF;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SignInPostController extends WebController
{
    public function view(Request $request): RedirectResponse
    {
        $validationErrors = $this->validate($request);

        return $validationErrors->count()
            ? $this->redirectWithErrors('sign_in', $validationErrors, $request)
            : $this->executeService($request);
    }

    protected function validate(Request $request): ConstraintViolationListInterface
    {
        $assertions = [
            '_csrf_token' => [new CSRF('sign-in')],
            'namespace'   => [new Assert\NotBlank(), new Assert\Length(['max' => 50]), new Assert\Type('alnum')],
        ];

        return $this->validateRequest($request, $assertions);
    }

    private function executeService(Request $request): RedirectResponse
    {
        $command = new SignInNamespaceDTO($request->request->getAlnum('namespace'));
        try{
            $this->dispatch($command);
        } catch (CompanyNotFound $exception){
            return $this->redirectWithError('sign_in', 'Namespace not found', $request);
        }

        return $this->redirect($command->uri());
    }
}
