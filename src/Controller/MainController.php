<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{   
    /**
     * @return Response
     */
    public function index()
    {
        $user=$this->getUser();
        $date = date('Y-m-d');
        //check if user is connected
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
      
            $em = $this->getDoctrine()->getManager();
            $events = $em->getRepository('App:Event')->findByDate($date);
            $users = $em->getRepository('App:User')->findAll();
            $albums = $em->getRepository('App:Album')->findAll();
            
            //retreive all updates
            $infos = array();
            //$infos = array_fill("first","test","https://google.fr");
            foreach($users as $user){
                if($user->getUpdatedDate() != null){
                    $new = array("name" => $user->getUsername(),"date"=>$user->getUpdatedDate(),"description"=>"a mis à jour son profil","link"=>"profile","link_attr"=>$user->getId());
                    array_push($infos,$new);
                }
            }
            foreach($events as $event){
                $description = "a crée un évènement: ".$event->getName();
                $new = array("name" => $event->getCreator()->getUsername(),"date"=>$event->getCreatedDate(),"description"=>$description,"link"=>"event","link_attr"=>$event->getId());
                array_push($infos,$new);
                if($event->getUpdatedDate() != null)
                {   $description = "a mis à jour un évènement: ".$event->getName();
                    $new = array("name" => $event->getCreator()->getUsername(),"date"=>$event->getUpdatedDate(),"description"=>$description,"link"=>"event","link_attr"=>$event->getId());
                    array_push($infos,$new);
                }
            }
             foreach($albums as $album){
                    $new = array("name" => $user->getUsername(),"date"=>$album->getUpdatedDate(),"description"=>"a crée un album : ".$album->getName(),"link"=>"album","link_attr"=>$album->getId());
                    array_push($infos,$new);
            }
            
            return $this->render('main/body.html.twig', array(
                'user' => $user,
                'events' => $events,
                'users' => $users,
                'notificationList' => $notificationList,
                'infos' => $infos
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function rules()
    {
        $user=$this->getUser();
        if(!$user) {
        return $this->render('main/rules.html.twig', array());
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);

            $em = $this->getDoctrine()->getManager();
            $date = date('Y-m-d');
            $events = $em->getRepository('App:Event')->findByDate($date);
            $users = $em->getRepository('App:User')->findAll();
                
            return $this->render('main/rules.html.twig', array(
                'user' => $user,
                'events' => $events,
                'users' => $users,
                'notificationList' => $notificationList
            ));
        
        }
    }
       

}
