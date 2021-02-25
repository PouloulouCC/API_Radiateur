<?php

namespace App\Controller;

use App\Entity\Period;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/addController", name="addController")
     * @param Request $request
     * @return Response
     */
    public function addController(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $controller = $em->getRepository('App:MicroController')->findOneBy(['macAddress' => $jsonData->mac]);

        $controller->addUser($user);

        $em->persist($user);
        $em->flush();

        return $this->json([
            'statusCode' => "ok",
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
            $response = new JsonResponse("error", "You are not authorized to access this data");
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
            'active' => $controller->getActive(),
            'periods' => $periods,
        ]);
    }

    /**
     * @Route("/getDataConfig", name="getDataConfig")
     * @param Request $request
     * @return Response
     */
    public function getDataConfig(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $controller = $em->getRepository('App:MicroController')->findOneBy(['macAddress' => $jsonData->mac]);

        if(!$controller->getUsers()->contains($user)) {
            $response = new JsonResponse("error", "You are not authorized to access this data");
            $response->setStatusCode(403);
            return $response;
        }

        $periods = $em->getRepository('App:Period')->findAllByControllerToArray($controller);

        return $this->json([
            'temp' => $controller->getCurrentTemperature(),
            'humidity' => $controller->getCurrentHumidity(),
            'tempExt' => $controller->getCurrentExtTemperature(),
            'humidityExt' => $controller->getCurrentExtHumidity(),
            'state' => $controller->getState(),
            'mode' => $controller->getMode(),
            'tempMax' => $controller->getTempMax(),
            'tempMin' => $controller->getTempMin(),
            'active' => $controller->getActive(),
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
        $controller->setTempMax($jsonData->tempMax);
        $controller->setTempMin($jsonData->tempMin);

//        $encoders = [new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//        $serializer = new Serializer($normalizers, $encoders);
//
//        $periods = $serializer->deserialize($jsonData->periods, Period::class, 'json');
        $jsonPeriods = $jsonData->periods;

        foreach($jsonPeriods as $jsonPeriod){
            $period = new Period();
            $period->setMicroController($controller);
            $period->setWeekDay($jsonPeriod->weekDay);
            $period->setTimeStart($jsonPeriod->timeStart);
            $period->setTimeEnd($jsonPeriod->timeEnd);
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
        $em = $this->getDoctrine()->getManager();

//        $encoders = [new JsonEncoder()];
//        $normalizers = [new ObjectNormalizer()];
//        $serializer = new Serializer($normalizers, $encoders);
//
//        $jsonControllers = $serializer->serialize($user->getMicroControllers(), 'json');
//        $user = $em->getRepository('App:User')->findOneById($user->getId());

        $jsonControllers = $em->getRepository('App:MicroController')->findAllByUserToArray($user);

        return $this->json([
            'controllers' => $jsonControllers,
        ]);
    }

    /**
     * @Route("/addPeriod", name="addPeriod")
     * @param Request $request
     * @return Response
     */
    public function addPeriod(Request $request): Response
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

        foreach ($jsonData->periods as $jsonPeriod) {

            $period = new Period();

            $period->setTimeStart($jsonData->timeStart);
            $period->setTimeEnd($jsonData->timeEnd);
            $period->setWeekDay($jsonData->weekDay);
            $period->setActive($jsonData->active);

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
     * @Route("/updatePeriod", name="updatePeriod")
     * @param Request $request
     * @return Response
     */
    public function updatePeriod(Request $request): Response
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

        foreach ($jsonData->periods as $jsonPeriod) {
            $period = $em->getRepository('App:Period')->findOneById($jsonData->id);

            if (!$controller->getPeriods()->contains($period)) {
                $response = new Response();
                $response->setStatusCode(403);
                return $response;
            }

            $period->setTimeStart($jsonData->timeStart);
            $period->setTimeEnd($jsonData->timeEnd);
            $period->setWeekDay($jsonData->weekDay);
            $period->setActive($jsonData->active);

            $em->persist($period);
        }
        $em->flush();

        return $this->json([
            'statusCode' => "ok",
        ]);
    }

    /**
     * @Route("/deletePeriod", name="deletePeriod")
     * @param Request $request
     * @return Response
     */
    public function deletePeriod(Request $request): Response
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

        foreach ($jsonData->periods as $jsonPeriod) {
            $period = $em->getRepository('App:Period')->findOneById($jsonData->id);

            if (!$controller->getPeriods()->contains($period)) {
                $response = new Response();
                $response->setStatusCode(403);
                return $response;
            }

            $controller->removePeriod($period);
        }
        $em->persist($controller);

        $em->flush();

        return $this->json([
            'statusCode' => "ok",
        ]);
    }

    /**
     * @Route("/activateController", name="activateController")
     * @param Request $request
     * @return Response
     */
    public function activateController(Request $request): Response
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

        $controller->setActive(true);

        $em->persist($controller);

        $em->flush();

        return $this->json([
            'statusCode' => "ok",
        ]);
    }

    /**
     * @Route("/deactivateController", name="deactivateController")
     * @param Request $request
     * @return Response
     */
    public function deactivateController(Request $request): Response
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

        $controller->setActive(false);

        $em->persist($controller);

        $em->flush();

        return $this->json([
            'statusCode' => "ok",
        ]);
    }

    /**
     * @Route("/updateMode", name="updateMode")
     * @param Request $request
     * @return Response
     */
    public function updateMode(Request $request): Response
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

        $controller->setMode($jsonData->mode);

        $em->persist($controller);

        $em->flush();

        return $this->json([
            'statusCode' => "ok",
        ]);
    }
}
