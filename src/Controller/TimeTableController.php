<?php
// src/Controller/TimeTableController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeTableController extends AbstractController
{
    /**
     * @return Response
     * @throws \Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     *
     * Call an extern api for ratp timetable
     */
    public function timetable()
    {
        //Metro 10 A-R
        $url = "https://api-ratp.pierre-grimaud.fr/v4/schedules/metros/10/boulogne-jean-jaures/A+R";
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {
            throw new \Exception("API error, not return 200");
        }
        // get Json as string
        $content = $response->getContent();
        //parse to php array
        $content = $response->toArray();


        //Bus 72 A-R
        $url2 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/72/route+de+la+reine+jean+jaures/A+R";
        $response = $client->request('GET', $url2);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {
            throw new \Exception("API error, not return 200");
        }
        // get Json as string
        $content2 = $response->getContent();
        //parse to php array
        $content2 = $response->toArray();

        //Bus 52 A-R
        $url3 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/52/jean+jaures/A+R";
        $response = $client->request('GET', $url3);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {
            throw new \Exception("API error, not return 200");
        }
        // get Json as string
        $content3 = $response->getContent();
        //parse to php array
        $content3 = $response->toArray();

        return $this->render('my/timetable.html.twig',array(
            'metro1' => $content['result']['schedules'],
            'bus1' => $content2['result']['schedules'],
            'bus2' => $content3['result']['schedules'],
        ));
    }


}
