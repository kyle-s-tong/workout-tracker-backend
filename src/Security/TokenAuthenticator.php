<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        return (('register' !== $request->get('_route') && $request->isMethod('POST')) || ('login' !== $request->get('_route') && $request->isMethod('POST'))) ? false : true;
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return array(
            'token' => $request->headers->get('x-authentication'),
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return;
        }

        $userToken = $this->em->getRepository(Token::class)
            ->findOneBy(['value' => $apiToken]);

        if (!$userToken) {
            return;
        } 

        $user = $userToken->getUser();
        if (!$user) {
            return;
        }

        // if a User object, checkCredentials() is called
        return $this->em->getRepository(User::class)
        ->find($user);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $apiToken = $credentials['token'];

        $token = $this->em->getRepository(Token::class)
        ->findOneBy(['value' => $apiToken]);
        
        $isEnabled = $token->getEnabled();
        $isExpired = $token->getExpiryDate() < new \DateTime();

        if ($isEnabled && !$isExpired) {
            // return true to cause authentication success
            return true;
        } 
        
        if ($isEnabled && $isExpired) {
            // disable the token because it has expired
            $token->setEnabled(false);
            $this->em->persist($token);
            $this->em->flush();
        }

        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            'message' => 'Authentication token is required to access this.'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}