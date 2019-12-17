<?php
// src/Controller/MapController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends Controller
{
    /**
     * @return Response
     */
    public function map()
    {
        //check if user is connected
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('App:User')->findAll();
            //select all lat and long of users address
            $allPos = array(array(NULL));
            $userName = array(NULL);
            $arrContextOptions = array(
                       "ssl" => array(
                           "verify_peer" => false,
                           "verify_peer_name" => false,
                       ),
                   );
            $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA0wuGfkqLD67jR6NfcC8mm4EuUROGis_I&address=%s&sensor=false";
            $i = 0;
            // Get latitude and longitude of the users address
            foreach($users as $user){
                if(!empty($user->getAddress())){
                    $query = sprintf($geocoder, urlencode(utf8_encode($user->getAddress())));
                    $result = json_decode(file_get_contents($query, false, stream_context_create($arrContextOptions)));

                    if (empty($result->results)) {
                        $latitude = 0;
                        $longitude = 0;
                    } else {
                        $json = $result->results[0];
                        $latitude = (float)$json->geometry->location->lat;
                        $longitude = (float)$json->geometry->location->lng;
                    }
                    $allPos[0][$i] = $latitude;
                    $allPos[1][$i] = $longitude;
                    $userName[$i] = $user->getUsername();
                    $i++;
                }
            }
            return $this->render('my/map/map.html.twig', array(
                'users' => $users,
                'userName' => $userName,
                'allPos' => $allPos,
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function mapJob()
    {
        //check if user is connected
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('App:User')->findAll();
            //select all lat and long of users address
            $allPos = array(array(NULL));
            $userName = array(NULL);
            $allName = array(NULL);
            $arrContextOptions = array(
                       "ssl" => array(
                           "verify_peer" => false,
                           "verify_peer_name" => false,
                       ),
                   );
            $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA0wuGfkqLD67jR6NfcC8mm4EuUROGis_I&address=%s&sensor=false";
            $i = 0;
            // Get latitude and longitude of the users address
            foreach($users as $user){
                if(!empty($user->getJob() and $user->getJob()->getAddress())){
                    $query = sprintf($geocoder, urlencode(utf8_encode($user->getJob()->getAddress())));
                    $result = json_decode(file_get_contents($query, false, stream_context_create($arrContextOptions)));

                    if (empty($result->results)) {
                        $latitude = 0;
                        $longitude = 0;
                    } else {
                        $json = $result->results[0];
                        $latitude = (float)$json->geometry->location->lat;
                        $longitude = (float)$json->geometry->location->lng;
                    }
                    $allPos[0][$i] = $latitude;
                    $allPos[1][$i] = $longitude;
                    $allName[$i] = $user->getUsername();
                    $userName[$i] = $user->getUsername() . " - " . $user->getJob()->getCompanyName();
                    $i++;
                }
            }
            return $this->render('my/map/map_job.html.twig', array(
                'users' => $users,
                'allName' => $allName,
                'userName' => $userName,
                'allPos' => $allPos,
                'notificationList' => $notificationList
            ));
        }
    }
    
     /**
     * @return Response
     */
    public function mapEvent()
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $events = $em->getRepository('App:Event')->findAll();
            //select all lat and long of users address
            $allPos = array(array(NULL));
            $allName = array(NULL);
            $arrContextOptions = array(
                       "ssl" => array(
                           "verify_peer" => false,
                           "verify_peer_name" => false,
                       ),
                   );
            $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA0wuGfkqLD67jR6NfcC8mm4EuUROGis_I&address=%s&sensor=false";
            $i = 0;
            // Get latitude and longitude of the users address
            foreach($events as $event){
                if(!empty($event->getAddress())){
                    $query = sprintf($geocoder, urlencode(utf8_encode($event->getAddress())));
                    $result = json_decode(file_get_contents($query, false, stream_context_create($arrContextOptions)));

                    if (empty($result->results)) {
                        $latitude = 0;
                        $longitude = 0;
                    } else {
                        $json = $result->results[0];
                        $latitude = (float)$json->geometry->location->lat;
                        $longitude = (float)$json->geometry->location->lng;
                    }
                    $allPos[0][$i] = $latitude;
                    $allPos[1][$i] = $longitude;
                    $allName[$i] = $event->getName();
                    $i++;
                }
            }
            return $this->render('my/map/map_event.html.twig', array(
                'allName' => $allName,
                'allPos' => $allPos,
                'notificationList' => $notificationList
            ));
        }
    }


}
