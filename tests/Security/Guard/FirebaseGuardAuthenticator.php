<?php

declare(strict_types=1);

namespace App\Tests\Security\Guard;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use function strtr;

final class FirebaseGuardAuthenticator extends AbstractGuardAuthenticator
{
    private TokenExtractorInterface $tokenExtractor;

    public function __construct(
        TokenExtractorInterface $tokenExtractor
    ) {
        $this->tokenExtractor = $tokenExtractor;
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
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $jsonWebToken = $this->tokenExtractor->extract($request);
        if ($jsonWebToken === false) {
            return null;
        }

        return (new Parser())->parse((string) $jsonWebToken);
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

        return $userProvider->loadUserByUsername($uid);
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
}
