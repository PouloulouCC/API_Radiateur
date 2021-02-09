<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MobileController
 * @package App\Controller
 * @route("/mobile")
 */
class MobileController extends AbstractController
{
    /**
     * @Route("/mobile", name="mobile")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MobileController.php',
        ]);
    }

    /**
     * @Route("/getData", name="mobile")
     */
    public function getData(): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $controller = $em->getRepository('App:MicroController')->findOneBy(['user' => $user]);

        //TODO: à finir
        return $this->json([
            'temp' => 20,
            'state' => $controller->getState(),
        ]);
    }
}
