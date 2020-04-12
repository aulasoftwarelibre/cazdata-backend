<?php

declare(strict_types=1);

namespace App\Securiy\Guard;

use App\Message\RegisterHunterFromCredentialsMessage;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Auth;
use Lcobucci\JWT\Token;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use function strtr;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class FirebaseGuardAuthenticator extends AbstractGuardAuthenticator
{
    use HandleTrait;

    private Auth $auth;
    private TokenExtractorInterface $tokenExtractor;

    public function __construct(
        Auth $auth,
        TokenExtractorInterface $tokenExtractor,
        MessageBusInterface $messageBus
    ) {
        $this->auth           = $auth;
        $this->tokenExtractor = $tokenExtractor;
        $this->messageBus     = $messageBus;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        $data = ['message' => 'Authentication Required'];

        return new JsonResponse($data, JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return $this->tokenExtractor->extract($request) !== false;
    }

    /**
     * @return Token|null
     *
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $jsonWebToken = $this->tokenExtractor->extract($request);
        if ($jsonWebToken === false) {
            return null;
        }

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($jsonWebToken);
        } catch (InvalidToken $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return $verifiedIdToken;
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Token $credentials
     *
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $uid = $credentials->getClaim('sub');
        try {
            $user = $userProvider->loadUserByUsername($uid);
        } catch (UsernameNotFoundException $e) {
            $user = $this->query(new RegisterHunterFromCredentialsMessage($credentials));
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }

    private function query(RegisterHunterFromCredentialsMessage $message) : UserInterface
    {
        return $this->handle($message);
    }
}
