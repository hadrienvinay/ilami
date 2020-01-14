<?php
// src/Controller/ResumeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GeocoderService;
use App\Entity\Event;
use App\Form\EventType;


class ResumeController extends Controller
{
    /**
     * @return Response
     */
    public function resume(Request $request, GeocoderService $geocoder)
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
            $users = $em->getRepository('App:User')->findAll();
            $events = $em->getRepository('App:Event')->findAll();

            $event = new Event();
            $event->setAddress($user->getAddress());
            $event->setLatitude($user->getLatitude());   
            $event->setLongitude($user->getLongitude());
            $event->setName("Soirée chez ".$user->getUsername());
            $eventForm = $this->get('form.factory')->create(EventType::class, $event);

            if ($request->isMethod('POST') && $eventForm->handleRequest($request)->isValid()) {
                //set lat and long
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
                $notif=$manager->createNotification($this->generateSubject($event),$event->getName(),'event/'.$event->getId());
                // you can add a notification to a list of entities
                // the third parameter ``$flush`` allows you to directly flush the entities
                $users = $em->getRepository('App:User')->findAll();
                foreach ($users as $to_notify)
                {
                    if ($to_notify != $this->getUser())
                    {
                        $manager->addNotification(array($to_notify), $notif, true);
                    }
                }

                $request->getSession()->getFlashBag()->add('success', 'Evènement créé avec succès !');
                return $this->redirectToRoute('resume');
            }
            
            
            return $this->render('my/resume.html.twig', array(
                'users' => $users,
                'events' => $events,
                'eventForm' => $eventForm->createView(),
                'notificationList' => $notificationList,
            ));
        }
    }

}
