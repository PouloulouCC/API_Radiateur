<?php

namespace App\Controller;

use App\Entity\MicroController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroControllerController extends AbstractController
{
    /**
     * @Route("/micro/controller", name="micro_controller")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MicroControllerController.php',
        ]);
    }

    /**
     * @Route("/updateData", name="update_data")
     */
    public function updateDataAction(Request $request): Response
    {
        $data = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $microController = new MicroController();
        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);

        $microController->addTemperatureRecord($data->temp);
        $microController->setState($data->state);

        $em->persist($microController);
        $em->flush();

        return new JsonResponse(
            array(
                "mode" => $microController->getMode(),
                "tempMax" => $microController->getTempMax(),
                "tempMin" => $microController->getTempMin(),
                "hours" => $microController->getHours(),
            )
        );
    }

    /**
     * @Route("/registerController", name="register_controller")
     */
    public function registerControllerAction(Request $request): Response
    {
        dump($request->getContent());
        $data = json_decode($request->getContent());
        dump($data);
        dump(json_last_error_msg());

        $em = $this->getDoctrine()->getManager();

        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);

        dump($microController);
//        dump($microController->length);

        if($microController == null){

            $microController = new MicroController();

            $microController->setMacAddress($data->mac);
            $microController->setState(false);
            $microController->setMode(5);
            $microController->setTempMax(null);
            $microController->setTempMin(null);

            $em->persist($microController);
            $em->flush();
        }

        return new JsonResponse(
            array(
                "mac"=> $microController->getMacAddress(),
            )
        );
    }

    /**
     * @Route("/getData", name="get_data")
     */
    public function getDataAction(Request $request): Response
    {
        $data = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $microController = new MicroController();
        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);



        if($microController != null) {
            return new JsonResponse(
                array(
                    "temp" => $microController->getTemperature(),
                    "tempMax" => $microController->getTempMax(),
                    "tempMin" => $microController->getTempMin(),
                    "hours" => $microController->getHours(),
                )
            );
        }
        $response = new Response();
        $response->setStatusCode(404);
        return $response;
    }

    /**
     * @Route("/getConfig", name="get_config")
     */
    public function getConfigAction(Request $request): Response
    {
        $data = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $microController = new MicroController();
        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);

        if($microController != null) {
            return new JsonResponse(
                array(
                    "mode" => $microController->getMode(),
                    "tempMax" => $microController->getTempMax(),
                    "tempMin" => $microController->getTempMin(),
                    "hours" => $microController->getHours(),
                )
            );
        }
        $response = new Response();
        $response->setStatusCode(404);
        return $response;
    }

    /**
     * @Route("/updateConfig", name="get_config")
     */
    public function updateConfigAction(Request $request): Response
    {
        $data = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $microController = new MicroController();
        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);

        if ($microController != null) {
            $microController->setMacAddress($data->mac);
            $microController->setMode($data->mode);
            $microController->setTempMax($data->tempMax);
            $microController->setTempMin($data->tempMin);
            $microController->setHours($data->hours);

            $response = new Response();
            $response->setStatusCode(204);
            return $response;
        }
        $response = new Response();
        $response->setStatusCode(404);
        return $response;
    }
}
