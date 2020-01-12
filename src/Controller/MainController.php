<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GeocoderService;
use App\Entity\Event;
use App\Entity\Album;
use App\Entity\Picture;
use App\Entity\Song;
use App\Form\EventType;
use App\Form\AlbumType;
use App\Form\PictureType;
use App\Form\SongType;


class MainController extends Controller
{   
    /**
     * @return Response
     */
    public function index(Request $request, GeocoderService $geocoder)
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
            $pictures = $em->getRepository('App:Picture')->findAll();
            $songs = $em->getRepository('App:Song')->findAll();

            //retreive all updates
            $infos = array();
            //$infos = array_fill("first","test","https://google.fr");
            foreach($users as $userInfo){
                if($userInfo->getUpdatedDate() != null){
                    $new = array("name" => $userInfo->getUsername(),"date"=>$userInfo->getUpdatedDate(),"description"=>"a mis à jour son profil","link"=>"profile","link_attr"=>$userInfo->getId());
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
                    $new = array("name" => $album->getCreator()->getUsername(),"date"=>$album->getUpdatedDate(),"description"=>"a crée un album : ".$album->getName(),"link"=>"album","link_attr"=>$album->getId());
                    array_push($infos,$new);
            }
            foreach($pictures as $picture){
                    $new = array("name" => $picture->getPublisher()->getUsername(),"date"=>$picture->getCreatedDate(),"description"=>"a ajouté une photo : ".$picture->getDescription(),"link"=>"gallery");
                    array_push($infos,$new);
            }
            foreach($songs as $song){
                    $new = array("name" => $song->getUploader()->getUsername(),"date"=>$song->getCreatedDate(),"description"=>"a importé une musique : ".$song->getFileName(),"link"=>"radio");
                    array_push($infos,$new);
            }
            
            //add event form
            $event = new Event();
            $event->setAddress($user->getAddress());
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
            
            //Event Form
            if($request->isMethod('POST') && $eventForm->handleRequest($request)->isValid()){
                if($event->getAddress() != $user->getAddress()){
                    $pos = $geocoder->convertAddress($event->getAddress());
                    $event->setLatitude($pos[0]);
                    $event->setLongitude($pos[1]);
                }
                $event->setCreatedDate(new \DateTime);
                $event->setCreator($user);
                $event->addParticipant($user);
                $em->persist($event);
                $em->flush();
                $manager = $this->get('mgilet.notification');
                $notif=$manager->createNotification($this->get('eventController')->generateSubject($event),$event->getName(),'event/'.$event->getId());
                foreach ($users as $to_notify)
                {
                    if ($to_notify != $this->getUser())
                    {
                        $manager->addNotification(array($to_notify), $notif, true);
                    }
                }
                $request->getSession()->getFlashBag()->add('success', 'Evènement créé avec succès !');
                return $this->redirectToRoute('main_app');
            }
            //Album Form
            $albumForm->handleRequest($request);
            if($request->isMethod('POST') && $albumForm->handleRequest($request)->isValid()){
                $fileName = $albumForm['presentationPicture']->getData();
                if($fileName){
                    $originalFilename = pathinfo($fileName->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$fileName->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $fileName->move(
                            $this->getParameter('album_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        throw $this->createNotFoundException(
                            'Erreur lors de l\'ajout de l\'album narvaloooo'
                        );
                    }

                    $album->setPresentationPicture($newFilename);   
                }
                else{
                    $album->setPresentationPicture('default.png');
                }
                $album->setCreator($user);
                $album->setUpdatedDate(new \DateTime);
                $em->persist($album);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Album créé avec succès !');

                return $this->redirectToRoute('main_app');
            }
            //Picture Form
            $pictureForm->handleRequest($request);
            if($request->isMethod('POST') && $pictureForm->handleRequest($request)->isValid()){
                $file = $pictureForm['files']->getData();
                if($file){
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                    try {
                        $file->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw $this->createNotFoundException(
                            'Erreur lors de l\'import narvaloooo'
                        );
                    }
                    $picture->setFileName($newFilename);
                    $picture->setDate(new \DateTime);
                    $picture->setPublisher($user);
                    $user->addPicture($picture);
                    $em->persist($picture);
                    $em->persist($user);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', 'Photo ajoutée avec succès !');
                }
                return $this->redirectToRoute('main_app');
            }
            //Song Form
            if($request->isMethod('POST') && $songForm->handleRequest($request)->isValid()){
                $songFile = $songform['file']->getData();
                $fileName = $songFile->getClientOriginalName();
                $songFile->move(
                    $this->getParameter('download_directory'),
                    $fileName
                );
                $song->setFileName($fileName);
                $song->setTitle("a");
                $song->setArtist("b");
                $song->setUploader($user);
                $song->setCreatedDate(new \DateTime());
                $em->persist($song);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Morceau ajouté avec succès !');
                return $this->redirectToRoute('main_app');
            }
          
            return $this->render('main/body.html.twig', array(
                'user' => $user,
                'events' => $events,
                'users' => $users,
                'infos' => $infos,
                'eventForm' => $eventForm->createView(),
                'albumForm' => $albumForm->createView(),
                'pictureForm' => $pictureForm->createView(),
                'songForm' => $songForm->createView(),
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
       

}
