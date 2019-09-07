<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Paknahad\JsonApiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpNotFoundException;


/**
 * @Route("/security")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, UserService $userService, EntityManagerInterface $entityManager): JsonResponse
    {
      $requestData = json_decode($request->getContent());
      $user = $entityManager->getRepository(User::class)
                  ->findOneByEmail($requestData->email);

      if (!$user) {
        throw new HttpNotFoundException('User not found');
      }

      $token = $userService->getExistingEnabledUserToken($user);
      if (!$token) {
          $token = $userService->generateAndSaveNewTokenForUser($user);
      }

      $response = new JsonResponse();
      $response->setData(
        [
          'authenticated' => true,
          'token' => $token->getValue(),
          'userId' => $user->getId()
        ]
      );

      return $response;
    }
}

