<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUserFromRequestHeader(Request $request)
    {
        try {
            $requestToken = $request->headers->get('x-authentication');

            $token = $this->em->getRepository(Token::class)
                ->findOneBy(['value' => $requestToken]);


            if (!$requestToken || !$token) {
                throw new UnauthorizedHttpException('Token not found');
            }

            $user = $token->getUser();

            return $user;
        } catch (UnauthorizedHttpException $e) {
            sprintf('Exception occurred %s', $e->getMessage());
        }
    }

    public function getExistingEnabledUserToken($user)
    {
        $enabledToken = $this->em->getRepository(Token::class)
                            ->findOneBy(['user' => $user->getId(),
                                         'enabled' => true]);

        if ($enabledToken) {
            //Disable all other tokens attached to that user.
            $this->disableRemainingTokens($user, $enabledToken);
        }

        return $enabledToken;
    }

    // TODO: Implement serializer here instead of setting everything manually.
    public function generateAndSaveNewTokenForUser($user)
    {
        $expiry = new \DateTime();
        $expiry->add(new \DateInterval('PT' . \strval(\ini_get('session.cookie_lifetime')). 'M'));

        $token = new Token();
        $token
            ->setValue(bin2hex(random_bytes(16)))
            ->setExpiryDate($expiry)
            ->setUser($user)
            ->setEnabled(true);

        $this->em->persist($token);
        $this->em->persist($user);
        
        $this->em->flush();

        return $token;
    }

    private function disableRemainingTokens($user, $enabledToken)
    {
        $enabledUserTokens = $this->em->getRepository(Token::class)
                    ->findBy(['user' => $user->getId()]);

        foreach ($enabledUserTokens as $userToken) {
            if ($userToken->getValue() !== $enabledToken->getValue()) {
                $userToken->setEnabled(false);
                $this->em->persist($userToken);
            }
        }

        $this->em->flush();
    }
}
