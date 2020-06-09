<?php
// src/Controller/CalendarController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends Controller
{
    /**
     * @return Response
     * @throws \Exception
     */
    public function calendar()
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
            $events = $em->getRepository('App:Event')->findAll();

            return $this->render('my/calendar.html.twig', [
                'users' => $users,
                'events' => $events,
                'notificationList' => $notificationList
            ]);
        }
    }
    
    /**
     * 
     */
    public function newsCalendar()
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
            $eventsRepo = $em->getRepository('App:Event');
            $events = $eventsRepo->findAll();
            $usersRepo = $em->getRepository('App:User');
            $users = $usersRepo->findAll();
            $albumsRepo = $em->getRepository('App:Album');
            $albums = $albumsRepo->findAll();
            $nbAlbums = $albumsRepo->countAll();
            $picturesRepo = $em->getRepository('App:Picture');
            $pictures = $picturesRepo->findAll();
            $nbPictures = $picturesRepo->countAll();
            $songsRepo = $em->getRepository('App:Song');
            $songs = $songsRepo->findAll();
            $nbSongs = $songsRepo->countAll();
            $recommandationRepo = $em->getRepository('App:Recommandation');
            $recommandations = $recommandationRepo->findAll();
            $nbRecommandations = $recommandationRepo->countAll();
            $mediaRepo = $em->getRepository('App:Media');
            $medias = $mediaRepo->findAll();
            $nbMedias = $mediaRepo->countAll();
            
            //retreive all updates
            $infos = array();
            foreach($users as $userInfo){
                if($userInfo->getUpdatedDate() != null){
                    $new = array("name" => $userInfo->getUsername(),"date"=>$userInfo->getUpdatedDate(),"description"=>"a mis à jour son profil","link"=>"profile","link_attr"=>$userInfo->getId(),"type"=>"Profil");
                    array_push($infos,$new);
                }
            }
            foreach($events as $event){
                $description = "a crée un évènement: ".$event->getName();
                $new = array("name" => $event->getCreator()->getUsername(),"date"=>$event->getCreatedDate(),"description"=>$description,"link"=>"event","link_attr"=>$event->getId(),"type"=>"Event");
                array_push($infos,$new);
                if($event->getUpdatedDate() != null)
                {   $description = "a mis à jour un évènement: ".$event->getName();
                    $new = array("name" => $event->getCreator()->getUsername(),"date"=>$event->getUpdatedDate(),"description"=>$description,"link"=>"event","link_attr"=>$event->getId(),"type"=>"Event");
                    array_push($infos,$new);
                }
            }
             foreach($albums as $album){
                    $new = array("name" => $album->getCreator()->getUsername(),"date"=>$album->getUpdatedDate(),"description"=>"a crée un album : ".$album->getName(),"link"=>"album","link_attr"=>$album->getId(),"type"=>"Album");
                    array_push($infos,$new);
            }
            foreach($pictures as $picture){
                    $new = array("name" => $picture->getPublisher()->getUsername(),"date"=>$picture->getCreatedDate(),"description"=>"a ajouté une photo : ".$picture->getDescription(),"link"=>"gallery","type"=>"Picture");
                    array_push($infos,$new);
            }
            foreach($songs as $song){
                    $new = array("name" => $song->getUploader()->getUsername(),"date"=>$song->getCreatedDate(),"description"=>"a importé une musique : ".$song->getFileName(),"link"=>"radio","type"=>"Song");
                    array_push($infos,$new);
            }
            foreach($recommandations as $recommandation){
                    $new = array("name" => $recommandation->getUser()->getUsername(),"date"=>$recommandation->getCreatedDate(),"description"=>"a ajouté une recommandation : ".$recommandation->getName(),"link"=>"profile",'link_attr'=>$recommandation->getUser()->getId(),"type"=>"Recommandation");
                    array_push($infos,$new);
            }
            foreach($medias as $media){
                    $new = array("name" => $media->getUploader()->getUsername(),"date"=>$media->getCreatedDate(),"description"=>"a ajouté un média : ".$media->getTitle(),"link"=>"media","type"=>"Media");
                    array_push($infos,$new);
            }

            return $this->render('my/calendar_news.html.twig', [
                'users' => $users,
                'infos' => $infos,
                'notificationList' => $notificationList
            ]);
        }
    }
}
