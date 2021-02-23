<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * @Route("/login_check", name="login")
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json(array(
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ));
    }

    /**
     * @Route("/register", name="login")
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $jsonData = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $user->setEmail($jsonData->email);
        $user->setPassword($passwordEncoder->encodePassword($user, $jsonData->password));
        $user->setFirstName($jsonData->firstName);
        $user->setLastName($jsonData->lastName);
        $user->setRoles(["ROLE_MOBILE"]);

        $em->persist($user);

        $em->flush();

        //TODO vérifier ça
        return $this->json(array(
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ));
    }
}
