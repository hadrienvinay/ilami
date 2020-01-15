<?php
// src/Controller/MapController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            //Users Pos
            $allPos = array(array(NULL));
            $userName = array(NULL);
            $i = 0;
            foreach($users as $user){
                if(!empty($user->getAddress())){
                    $allPos[0][$i] = $user->getLatitude();
                    $allPos[1][$i] = $user->getLongitude();
                    $userName[$i] = $user->getUsername();
                    $i++;
                }
            }
            //Events Pos
            $events = $em->getRepository('App:Event')->findAll();
            //select all lat and long of users address
            $allPos2 = array(array(NULL));
            $allName2 = array(NULL);
            $i = 0;
            foreach($events as $event){
                if(!empty($event->getAddress())){
                    $allPos2[0][$i] = $event->getLatitude();
                    $allPos2[1][$i] = $event->getLongitude();
                    $allName2[$i] = $event->getName();
                    $i++;
                }
            }
            //Jobs Pos
            $allPos3 = array(array(NULL));
            $userName3 = array(NULL);
            $allName3 = array(NULL);
            $i = 0;
            foreach($users as $user){
                if(!empty($user->getJob() and $user->getJob()->getAddress())){
                    $allPos3[0][$i] = $user->getJob()->getLatitude();
                    $allPos3[1][$i] = $user->getJob()->getLongitude();
                    $allName3[$i] = $user->getUsername();
                    $userName3[$i] = $user->getUsername() . " - " . $user->getJob()->getCompanyName();
                    $i++;
                }
            }
            //Recommandation Pos
            $recommandations = $em->getRepository('App:Recommandation')->findAll();

            $allPos4 = array(array(NULL));
            $allName4 = array(NULL);
            $i = 0;
            foreach($recommandations as $recommandation){
                if(!empty($recommandation->getAddress())){
                    $allPos4[0][$i] = $recommandation->getLatitude();
                    $allPos4[1][$i] = $recommandation->getLongitude();
                    $allName4[$i] = $recommandation->getName() . " - " . $recommandation->getType();
                    $i++;
                }
            }
            
            return $this->render('my/map/map.html.twig', array(
                'users' => $users,
                'userName' => $userName,
                'allPos' => $allPos,
                'allName2' => $allName2,
                'allPos2' => $allPos2,
                'allName3' => $allName3,
                'userName3' => $userName3,
                'allPos3' => $allPos3,
                'allPos4' => $allPos4,
                'allName4' => $allName4,
                'notificationList' => $notificationList
            ));
        }
    }
    
}
