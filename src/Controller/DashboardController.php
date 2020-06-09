<?php
// src/Controller/DashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends Controller
{
    
    public function home(){
        
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository('App:User');
        $users = $usersRepo->findAll();
        $eventsRepo = $em->getRepository('App:Event');
        $events = $eventsRepo->findAll();
        
        //API Calls
        $client = HttpClient::create();
        //Metro 10 A-R
        $url = "https://api-ratp.pierre-grimaud.fr/v4/schedules/metros/10/boulogne-jean-jaures/A+R";
        $response = $client->request('GET', $url);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {throw new \Exception("API error, not return 200");}
        $content = $response->getContent();
        $content = $response->toArray();

        //Bus 72 A-R
        $url2 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/72/route+de+la+reine+jean+jaures/A+R";
        $response = $client->request('GET', $url2);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {throw new \Exception("API error, not return 200");}
        $content2 = $response->getContent();
        $content2 = $response->toArray();

        //Bus 52 A-R
        $url3 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/52/jean+jaures/A+R";
        $response = $client->request('GET', $url3);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {throw new \Exception("API error, not return 200");}
        $content3 = $response->getContent();
        $content3 = $response->toArray();
        
        //Weather in Paris
        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q=Paris&appid=4cc34575f07df4fbc95c176f87a47559";
        $response = $client->request('GET', $weatherUrl);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {throw new \Exception("Weather API error, not return 200");}
        $content4 = $response->getContent();
        $content4 = $response->toArray();
        dump($content4);
        
        return $this->render('my/dashboard/home.html.twig',array(
            'users' => $users,
            'events' => $events,
            'metro1' => $content['result']['schedules'],
            'bus1' => $content2['result']['schedules'],
            'bus2' => $content3['result']['schedules'],
            'weather' => $content4,
        ));
    }
    
    /**
     * @return Response
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
        {throw new \Exception("API error, not return 200");}
        // get Json as string
        $content = $response->getContent();
        //parse to php array
        $content = $response->toArray();

        //Bus 72 A-R
        $url2 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/72/route+de+la+reine+jean+jaures/A+R";
        $response = $client->request('GET', $url2);
        $statusCode = $response->getStatusCode();
        //if ($statusCode != 200)
        //{throw new \Exception("API error, not return 200");}
        $content2 = $response->getContent();
        $content2 = $response->toArray();

        //Bus 52 A-R
        $url3 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/52/jean+jaures/A+R";
        $response = $client->request('GET', $url3);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {throw new \Exception("API error, not return 200");}
        $content3 = $response->getContent();
        $content3 = $response->toArray();

        return $this->render('my/dashboard/timetable.html.twig',array(
            'metro1' => $content['result']['schedules'],
            'bus1' => $content2['result']['schedules'],
            'bus2' => $content3['result']['schedules'],
        ));
    }
    
    /**
     * @return Response
     * Call an extern api for weather
     */
    public function weather()
    {
    
    }
    


}
