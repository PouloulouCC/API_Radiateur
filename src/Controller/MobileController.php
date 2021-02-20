<?php

namespace App\Controller;

use App\Entity\Period;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * @Route("/getData", name="getData")
     * @param Request $request
     * @return Response
     */
    public function getData(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $controller = $em->getRepository('App:MicroController')->findOneBy(['macAddress' => $jsonData->mac]);

        if(!$controller->getUsers()->contains($user)) {
            $response = new Response();
            $response->setStatusCode(403);
            return $response;
        }

        return $this->json([
            'temp' => $controller->getCurrentTemperature(),
            'humidity' => $controller->getCurrentHumidity(),
            'tempExt' => $controller->getCurrentExtTemperature(),
            'humidityExt' => $controller->getCurrentExtHumidity(),
            'state' => $controller->getState(),
        ]);
    }

    /**
     * @Route("/getConfig", name="getConfig")
     * @param Request $request
     * @return Response
     */
    public function getConfig(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $controller = $em->getRepository('App:MicroController')->findOneBy(['macAddress' => $jsonData->mac]);

        if(!$controller->getUsers()->contains($user)) {
            $response = new Response();
            $response->setStatusCode(403);
            return $response;
        }

//        $encoders = [new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//        $serializer = new Serializer($normalizers, $encoders);
//
//        $jsonPeriods = $serializer->serialize($controller->getPeriods(), 'json');
        $periods = $em->getRepository('App:Period')->findAllByControllerToArray($controller);

        return $this->json([
            'mode' => $controller->getMode(),
            'tempMax' => $controller->getTempMax(),
            'tempMin' => $controller->getTempMin(),
            'periods' => $periods,
        ]);
    }

    /**
     * @Route("/updateConfig", name="updateConfig")
     * @param Request $request
     * @return Response
     */
    public function updateConfig(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $controller = $em->getRepository('App:MicroController')->findOneBy(['macAddress' => $jsonData->mac]);

        if(!$controller->getUsers()->contains($user)) {
            $response = new Response();
            $response->setStatusCode(403);
            return $response;
        }

        foreach($controller->getPeriods() as $period){
            $em->remove($period);
        }

        $controller->setMode($jsonData->mode);
        $controller->setMode($jsonData->tempMax);
        $controller->setMode($jsonData->tempMin);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $periods = $serializer->deserialize($jsonData->periods, Period::class, 'json');

        foreach($periods as $period){
            $period->setMicroController($controller);
            $controller->addPeriod($period);
            $em->persist($period);
        }

        $em->persist($controller);
        $em->flush();

        return $this->json([
            'statusCode' => "ok",
        ]);
    }

    /**
     * @Route("/getControllers", name="getControllers")
     * @return Response
     */
    public function getControllers(): Response
    {
        $user = $this->getUser();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonControllers = $serializer->serialize($user->getMicroControllers(), 'json');

        return $this->json([
            'controllers' => $jsonControllers,
        ]);
    }
}
