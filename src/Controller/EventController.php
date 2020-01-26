<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Form\EventType;
use App\Form\PictureType;
use App\Service\GeocoderService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EventController extends Controller
{
    private $geocoder;

    public function __construct(GeocoderService $geocoder)
    {
        $this->geocoder = $geocoder;
    }
    
    /**
     * @return Response
     */
    public function event($id, Request $request)
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
            $event = $em->getRepository('App:Event')->find($id);
       
            if (!$event) {
                throw $this->createNotFoundException(
                    'Pas d\'évent trouvé narvaloo pour cet identifiant: '.$id
                );
            }
            
            //Event Form
            $prevAddress = $event->getAddress();
            $eventForm = $this->get('form.factory')->create(EventType::class, $event);
            if ($request->isMethod('POST') && $eventForm->handleRequest($request)->isValid()) {
                if($user != $event->getCreator()){
                    throw new AccessDeniedException('Tu peux pas modifier un event qui n\'est pas le tien narvalo !');
                }
                if($event->getAddress() != $prevAddress){
                    $pos = $this->geocoder->convertAddress($event->getAddress());
                    $event->setLatitude($pos[0]);
                    $event->setLongitude($pos[1]);
                }
                $event->setUpdatedDate(new \DateTime());
                $em->persist($event);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Evènement modifié avec succès !');
                return $this->redirectToRoute('event',array('id'=>$event->getId()));

            }
            //Picture Form
            $picture = new Picture();
            $picture->setEvent($event);
            $pictureForm = $this->get('form.factory')->create(PictureType::class, $picture);
            
            return $this->render('my/event.html.twig', array(
                'event' => $event,
                'eventForm' => $eventForm->createView(),
                'pictureForm' => $pictureForm->createView(),
                'notificationList' => $notificationList
            ));
        }
    }

    /**
     * @return Response
     */
    public function addevent(Request $request, $start, $end)
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

            date_default_timezone_set("Europe/Paris");
            
            $event = new Event();
            $event->setAddress($user->getAddress());
            $event->setLatitude($user->getLatitude());   
            $event->setLongitude($user->getLongitude());
            $event->setStartDate(new \DateTime());
            $event->setEndDate(new \DateTime());
            $event->setName("Soirée chez ".$user->getUsername());

            if (!is_null($start) and !is_null($end))
            {
                $event->setStartDate(new \DateTime(date('Y-m-d H:i:s', $start/1000)));
                $event->setEndDate(new \DateTime(date('Y-m-d H:i:s', $end/1000)));
            }

            $form = $this->get('form.factory')->create(EventType::class, $event);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                //set lat and long
                if($event->getAddress() != $user->getAddress()){
                    $pos = $this->geocoder->convertAddress($event->getAddress());
                    $event->setLatitude($pos[0]);
                    $event->setLongitude($pos[1]);
                }
                $event->setCreatedDate(new \DateTime());
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
                return $this->render('add/event.html.twig', array(
                    'form' => $form->createView(),
                    'notificationList' => $notificationList                 
            ));
        }
    }

    /**
     * @return Response
     */
    public function modifyEvent(Request $request, $id, GeocoderService $geocoder)
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
            $event = $em->getRepository('App:Event')->find($id);
            $prevAddress = $event->getAddress();

            if (!$event) {
                throw $this->createNotFoundException(
                    'Pas d\'évent trouvé narvaloo pour cet identifiant: '.$id
                );
            }

            $form = $this->get('form.factory')->create(EventType::class, $event);
            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                if ($prevAddress != $event->getAddress()){
                    $pos = $this->geocoder->convertAddress($event->getAddress());
                    $event->setLatitude($pos[0]);
                    $event->setLongitude($pos[1]);
                }
                $event->setUpdatedDate(new \DateTime());
                $em->persist($event);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Evènement modifié avec succès !');

                return $this->redirectToRoute('event',array('id' => $event->getId()));
            }

            return $this->render('modify/event.html.twig', array(
                'event' => $event,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function addParticipant(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository('App:Event')->find($id);

            if (!$event) {
                throw $this->createNotFoundException('Aucun event trouvé pour id: ' . $event);
            }
            
            $event->addParticipant($user);
            //$user->setEvents($event);
            $em->persist($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Participation confirmée avec succès !');

            return $this->redirectToRoute('event',array('id' => $id));
        }
    }
    
    /**
     * @return Response
     */
    public function cancelParticipant(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository('App:Event')->find($id);

            if (!$event) {
                throw $this->createNotFoundException('Aucun event trouvé pour id: ' . $event);
            }

            $event->removeParticipant($user);
            $em->persist($event);
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Participation annulée avec succès !');

            return $this->redirectToRoute('event',array('id' => $id));
            
        }
    }
    
    /**
     * @return Response
     */
    public function deleteEvent(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $event = $em->getRepository('App:Event')->find($id);
            if (!$event) {
                throw $this->createNotFoundException('Aucun event trouvé pour id: ' . $event);
            }
            // Security check
            if ($user != $event->getCreator()) {
                // else error page
                throw new AccessDeniedException('Tu ne peux pas supprimer un évènement que tu n\'as pas crée narvalo !');
            }
            
            $em->remove($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Evènement supprimé avec succès !');

            return $this->redirectToRoute('resume');
        }
    }

    public static function dateToFrench($date, $format)
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
    }

    public function isOnlyOneDay($start, $end)
    {
        if ($end->diff($start)->format("%a") >= 1)
        {
            return False;
        }
        return True;
    }

    public function generateSubject(Event $event)
    {
        $one_day_event = $this->isOnlyOneDay($event->getStartDate(), $event->getEndDate());

        $subject = $event->getCreator()->getUsername() . " a créé un évènement ";

        if ($one_day_event)
        {
            $subject .= "le " . self::dateToFrench(date_format($event->getStartDate(), "l d F"), "l d F");
        }
        else
        {
            $subject .= "du " . self::dateToFrench(date_format($event->getStartDate(), "l d F"), "l d F");
            $subject .= " au " . self::dateToFrench(date_format($event->getEndDate(), "l d F"), "l d F");
        }

        return $subject;
    }

}
