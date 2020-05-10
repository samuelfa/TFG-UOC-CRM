<?php

namespace App\Infrastructure\Symfony\Security;

use App\Domain\Employee\Employee;
use App\Domain\Employee\ManagerRepository;
use App\Domain\Employee\WorkerRepository;
use App\Domain\ValueObject\EmailAddress;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class Authenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private UrlGeneratorInterface $urlGenerator;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private WorkerRepository $workerRepository;
    private ManagerRepository $managerRepository;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        WorkerRepository $workerRepository,
        ManagerRepository $managerRepository
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->workerRepository = $workerRepository;
        $this->managerRepository = $managerRepository;
    }

    public function supports(Request $request): bool
    {
        return 'crm_login_post' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email_address' => $request->request->get('email_address'),
            'password' => $request->request->get('password'),
            '_csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email_address']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider): Employee
    {
        $token = new CsrfToken('login', $credentials['_csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Invalid CSRF');
        }

        $emailAddress = new EmailAddress($credentials['email_address']);
        $worker = $this->workerRepository->findOneByEmailAddress($emailAddress);
        if ($worker) {
            return $worker;
        }

        $manager = $this->managerRepository->findOneByEmailAddress($emailAddress);
        if ($manager) {
            return $manager;
        }

        // fail authentication with a custom error
        throw new CustomUserMessageAuthenticationException('Email could not be found.');
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param array $credentials
     * @return string|null
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if (($targetPath = $this->getTargetPath($request->getSession(), $providerKey))
            && strpos($targetPath, $GLOBALS['namespace']) !== false) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('crm_dashboard'));
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('crm_login');
    }
}
