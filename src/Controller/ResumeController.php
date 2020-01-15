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
            $event->setStartDate(new \DateTime());
            $event->setEndDate(new \DateTime());
            $event->setName("Soirée chez ".$user->getUsername());
            $eventForm = $this->get('form.factory')->create(EventType::class, $event);
            
            return $this->render('my/resume.html.twig', array(
                'users' => $users,
                'events' => $events,
                'eventForm' => $eventForm->createView(),
                'notificationList' => $notificationList,
            ));
        }
    }

}
