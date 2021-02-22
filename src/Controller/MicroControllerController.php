<?php

namespace App\Controller;

use App\Entity\MicroController;
use App\Entity\TempHumidityRecord;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

//use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class CompanyController
 * @package App\Controller
 * @route("/controller")
 */
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
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function updateDataAction(Request $request): Response
    {
        $data = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $microController = new MicroController();
        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);

        $hour = false;
        if($microController != null) {

            if($microController->getCity() != null) {

                $tempHumidityRecord = new TempHumidityRecord();

//            dump("diff time : " . $microController->getApiLastCall()->diff(new DateTime())->format('%f'));
                $today = new DateTime();
                foreach($microController->getPeriods() as $period){
                    if($period->getActive()) {
                        if ($period->getWeekDay() == $today->format('N')) {
                            $startTime = $period->getTimeStart()->format('%H:i:s');
                            $endTime = $period->getTimeEnd()->format('%H:i:s');
                            $todayTime = $today->format('%H:i:s');
                            if ($todayTime > $startTime && $todayTime < $endTime) {
                                $hour = true;
                                break;
                            }
                        }
                    }
                }

                if ($microController->getApiLastCall()->diff(new DateTime())->format('%f') > 300000) {

//                dump("test");
                    $httpClient = HttpClient::create();
                    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=". $microController->getCity() ."&units=metric&appid=67e9953936d4c9516073368ca7810b5f";
                    $response = $httpClient->request(
                        'GET',
                        $apiUrl
                    );

//                dump($response->getContent());

                    $weatherData = json_decode($response->getContent());

                    $microController->setApiLastCall(new DateTime());
                    $microController->setCurrentExtTemperature($weatherData->main->temp);
                    $microController->setCurrentExtHumidity($weatherData->main->humidity);
                }



                $tempHumidityRecord->setMicroController($microController);
                $tempHumidityRecord->setTemperatureInt($data->temp);
                $tempHumidityRecord->setTemperatureExt($microController->getCurrentExtTemperature());
                $tempHumidityRecord->setHumidityInt($data->humidity);
                $tempHumidityRecord->setHumidityExt($microController->getCurrentExtHumidity());
                $tempHumidityRecord->setTimeStamp(new DateTime());

                $microController->addTempHumidityRecord($tempHumidityRecord);
                $microController->setState($data->state);
                $microController->setCurrentTemperature($data->temp);
                $microController->setCurrentHumidity($data->humidity);

                $em->persist($microController);
                $em->persist($tempHumidityRecord);
                $em->flush();
            }

            return new JsonResponse(
                array(
                    "mode" => $microController->getMode(),
                    "tempMax" => $microController->getTempMax(),
                    "tempMin" => $microController->getTempMin(),
                    "hour" => $hour,
                    "active" => $microController->getActive(),
                )
            );
        }else{
            $res = new Response();
            $res->setStatusCode(404);
            return $res;
        }
    }

    /**
     * @Route("/registerController", name="register_controller")
     */
    public function registerControllerAction(Request $request): Response
    {
//        dump($request->getContent());
        $data = json_decode($request->getContent());
//        dump($data);
//        dump(json_last_error_msg());

        $em = $this->getDoctrine()->getManager();

        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);

//        dump($microController);
//        dump($microController->length);

        if($microController == null){

            $microController = new MicroController();

            $microController->setMacAddress($data->mac);
            $microController->setState(false);
            $microController->setMode(5);
            $microController->setTempMax(null);
            $microController->setTempMin(null);
            $microController->setTemperature(0.0);

            $em->persist($microController);
            $em->flush();
        }

        return new JsonResponse(
            array(
                "mac"=> $microController->getMacAddress(),
            )
        );
    }

//    /**
//     * @Route("/getData", name="get_data")
//     */
//    public function getDataAction(Request $request): Response
//    {
//        $data = json_decode($request->getContent());
//        $em = $this->getDoctrine()->getManager();
//
//        $microController = new MicroController();
//        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);
//
//
//
//        if($microController != null) {
//            return new JsonResponse(
//                array(
//                    "temp" => $microController->getCurrentTemperature(),
//                    "tempMax" => $microController->getTempMax(),
//                    "tempMin" => $microController->getTempMin(),
//                    "hours" => $microController->getHours(),
//                )
//            );
//        }
//        $response = new Response();
//        $response->setStatusCode(404);
//        return $response;
//    }

//    /**
//     * @Route("/getConfig", name="get_config")
//     */
//    public function getConfigAction(Request $request): Response
//    {
//        $data = json_decode($request->getContent());
//        $em = $this->getDoctrine()->getManager();
//
//        $microController = new MicroController();
//        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);
//
//        if($microController != null) {
//            return new JsonResponse(
//                array(
//                    "mode" => $microController->getMode(),
//                    "tempMax" => $microController->getTempMax(),
//                    "tempMin" => $microController->getTempMin(),
//                    "hours" => $microController->getHours(),
//                )
//            );
//        }
//        $response = new Response();
//        $response->setStatusCode(404);
//        return $response;
//    }

//    /**
//     * @Route("/updateConfig", name="update_config")
//     */
//    public function updateConfigAction(Request $request): Response
//    {
//        $data = json_decode($request->getContent());
//        $em = $this->getDoctrine()->getManager();
//
//        $microController = new MicroController();
//        $microController = $em->getRepository("App:MicroController")->findOneBy(['macAddress' => $data->mac]);
//
//        if ($microController != null) {
//            $microController->setMacAddress($data->mac);
//            $microController->setMode($data->mode);
//            $microController->setTempMax($data->tempMax);
//            $microController->setTempMin($data->tempMin);
//            $microController->setHours($data->hours);
//
//            $response = new Response();
//            $response->setStatusCode(204);
//            return $response;
//        }
//        $response = new Response();
//        $response->setStatusCode(404);
//        return $response;
//    }
}
