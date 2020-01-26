<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Event;
use App\Entity\Album;
use App\Entity\Picture;
use App\Entity\Song;
use App\Entity\Media;
use App\Form\EventType;
use App\Form\AlbumType;
use App\Form\PictureType;
use App\Form\SongType;
use App\Form\MediaType;
use DateTime;

class MainController extends Controller
{
    /**
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $user=$this->getUser();
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
            
            //Sort infos array by Date 
            usort($infos, function($a, $b) {
                $ad = ($a['date']);
                $bd = ($b['date']);
                if ($ad == $bd) {
                  return 0;
                }
                return $ad > $bd ? -1 : 1;
              });
            
            $infosPage = $paginator->paginate(
                $infos, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                20 // Nombre de résultats par page
            );
            
            //add event form
            $event = new Event();
            $event->setAddress($user->getAddress());
            $event->setStartDate(new \DateTime());
            $event->setEndDate(new \DateTime());
            $event->setLatitude($user->getLatitude());   
            $event->setLongitude($user->getLongitude());
            $event->setName("Soirée chez ".$user->getUsername());
            $eventForm = $this->get('form.factory')->create(EventType::class, $event);
            //add album form
            $album = new Album();
            $albumForm = $this->get('form.factory')->create(AlbumType::class, $album);
            //add picture form
            $picture = new Picture();
            $pictureForm = $this->get('form.factory')->create(PictureType::class, $picture);
            //add song form
            $song = new Song();
            $songForm = $this->get('form.factory')->create(SongType::class, $song);
            //add song form
            $media = new Media();
            $mediaForm = $this->get('form.factory')->create(MediaType::class, $media);
            
            return $this->render('main/body.html.twig', array(
                'user' => $user,
                'events' => $events,
                'users' => $users,
                'infosPage' => $infosPage,
                'nbAlbums' => $nbAlbums,
                'nbPictures' => $nbPictures,
                'nbSongs' => $nbSongs,
                'nbMedias' => $nbMedias,
                'nbRecommandations' => $nbRecommandations,
                'eventForm' => $eventForm->createView(),
                'albumForm' => $albumForm->createView(),
                'pictureForm' => $pictureForm->createView(),
                'songForm' => $songForm->createView(),
                'mediaForm' => $mediaForm->createView(),
                'notificationList' => $notificationList,

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
    
    /**
     * @param Request $request
     * @return \App\Controller\JsonResponse
     */
    public function autocompleteSearch(Request $request){
        $names = array();
        $term = trim(strip_tags($request->get('term')));

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->createQueryBuilder('c')
           ->where('c.username LIKE :username')
           ->setParameter('username', '%'.$term.'%')
           ->getQuery()
           ->getResult();

        foreach ($users as $user)
        {
            $names[] = array('value'=>$user->getUsername(),'label'=>$user->getId(),'category'=>'user');
        }
        
        $events = $em->getRepository('App:Event')->createQueryBuilder('c')
           ->where('c.name LIKE :name')
           ->setParameter('name', '%'.$term.'%')
           ->getQuery()
           ->getResult();
        
        foreach ($events as $event)
        {
            $names[] = array('value'=>$event->getName(),'label'=>$event->getId(),'category'=>'event');
        }
        
        $albums = $em->getRepository('App:Album')->createQueryBuilder('c')
           ->where('c.name LIKE :name')
           ->setParameter('name', '%'.$term.'%')
           ->getQuery()
           ->getResult();
        
        foreach ($albums as $album)
        {
            $names[] = array('value'=>$album->getName(),'label'=>$album->getId(),'category'=>'album');
        }
        
        $response = new JsonResponse();
        $response->setData($names);

        return $response;
    }
       

}
