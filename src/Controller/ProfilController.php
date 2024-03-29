<?php
// src/Controller/ProfilController.php
namespace App\Controller;

use App\Form\UserType;
use App\Form\RecommandationType;
use App\Form\JobType;
use App\Form\MediaType;
use App\Form\PictureType;
use App\Form\SongType;
use App\Form\EventType;
use App\Service\GeocoderService;
use App\Entity\Recommandation;
use App\Entity\Media;
use App\Entity\Job;
use App\Entity\Song;
use App\Entity\Picture;
use App\Entity\Event;
use FOS\UserBundle\Form\Type\ChangePasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Knp\Component\Pager\PaginatorInterface;

class ProfilController extends Controller
{
    private $geocoder;

    public function __construct(GeocoderService $geocoder)
    {
        $this->geocoder = $geocoder;
    }
    
    /**
     * @return Response
     */
    public function profil(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->find($id);
            if (!$user) {
                throw $this->createNotFoundException(
                    'Pas d\'utilisateur trouvé narvaloo pour cet identifiant: '.$id
                );
            }
            
            $prevAddress = $user->getAddress();
            $form = $this->get('form.factory')->create(UserType::class, $user);
            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                if($user != $this->getUser()){
                    throw new AccessDeniedException('Tu peux pas modifier un autre profil que le tien narvaloo !');
                }
                if ($prevAddress != $user->getAddress()){
                    $pos = $this->geocoder->convertAddress($user->getAddress());
                    $user->setLatitude($pos[0]);
                    $user->setLongitude($pos[1]);
                }
                $user->setUpdatedDate(new \DateTime);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Profil modifié avec succès !');
                return $this->redirectToRoute('profile',array('id'=>$user->getId()));
            }
            
            //picture form
            $picture = new Picture();
            $pictureForm = $this->get('form.factory')->create(PictureType::class, $picture);
            //song form
            $song = new Song();
            $songForm = $this->get('form.factory')->create(SongType::class, $song);
            //Job form
            $job = $user->getJob();
            if(!$job){$job = new Job();}
            $job->setStartDate(new \DateTime());
            $jobForm = $this->get('form.factory')->create(JobType::class, $job);
            //recommandation form
            $recommandation = new Recommandation();
            $recommandationform = $this->get('form.factory')->create(RecommandationType::class, $recommandation);
            //recommandation form
            $media = new Media();
            $mediaForm = $this->get('form.factory')->create(MediaType::class, $media);
            //Event Form
            $event = new Event();
            $event->setAddress($user->getAddress());
            $event->setLatitude($user->getLatitude());   
            $event->setLongitude($user->getLongitude());
            $event->setStartDate(new \DateTime());
            $event->setEndDate(new \DateTime());
            $event->setName("Soirée chez ".$user->getUsername());
            $eventForm = $this->get('form.factory')->create(EventType::class, $event);
            //change password form
            $passwordForm = $this->get('form.factory')->create(ChangePasswordFormType::class);
            $passwordForm->setData($user);
            
            return $this->render('my/profil.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'jobForm' => $jobForm->createView(),
                'pictureForm' => $pictureForm->createView(),
                'songForm' => $songForm->createView(),
                'recommandationForm' => $recommandationform->createView(),
                'mediaForm' => $mediaForm->createView(),
                'eventForm' => $eventForm->createView(),
                'passwordForm' => $passwordForm->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function modifyProfil(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->find($id);
            $prevAddress = $user->getAddress();
            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){}
            else if($user != $this->getUser()){
                throw new AccessDeniedException('Tu peux pas modifier un autre profil que le tien narvaloo !');
            }

            $form = $this->get('form.factory')->create(UserType::class, $user);
            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                if ($prevAddress != $user->getAddress()){
                    $pos = $this->geocoder->convertAddress($user->getAddress());
                    $user->setLatitude($pos[0]);
                    $user->setLongitude($pos[1]);
                }
                $user->setUpdatedDate(new \DateTime);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Profil modifié avec succès !');

                return $this->redirectToRoute('profile',array('id'=>$user->getId()));
            }

            return $this->render('modify/profil.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function addRecommandation(Request $request)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $recommandation = new Recommandation();
            $em = $this->getDoctrine()->getManager();
            $form = $this->get('form.factory')->create(RecommandationType::class, $recommandation);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pos = $this->geocoder->convertAddress($recommandation->getAddress());
                $recommandation->setLatitude($pos[0]);
                $recommandation->setLongitude($pos[1]);
                $recommandation->setUser($user);
                $recommandation->setCreatedDate(new \DateTime);
                $em->persist($recommandation);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Recommandation ajoutée avec succès !');

                return $this->redirectToRoute('profile',array('id'=>$user->getId()));

            }

            return $this->render('add/recommandation.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
     /**
     * @return Response
     */
    public function addJob(Request $request)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
                        
            $em = $this->getDoctrine()->getManager();
            $job = $user->getJob();
            if(!$job){
                $job = new Job();
            }
            $form = $this->get('form.factory')->create(JobType::class, $job);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pos = $this->geocoder->convertAddress($job->getAddress());
                $job->setLatitude($pos[0]);
                $job->setLongitude($pos[1]);
                $job->setUser($user);
                
                $em->persist($job);
                $user->setJob($job);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Job modifié avec succès !');

                return $this->redirectToRoute('profile',array('id'=>$user->getId()));
            }

            return $this->render('add/job.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    public function redirectToProfil(){
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            return $this->redirectToRoute('profile',array('id'=>$user->getId()));
        }
    }
    
}
