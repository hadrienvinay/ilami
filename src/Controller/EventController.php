<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EventController extends Controller
{
    /**
     * @return Response
     */
    public function event($id)
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
            $arrContextOptions = array(
                       "ssl" => array(
                           "verify_peer" => false,
                           "verify_peer_name" => false,
                       ),
                   );
            $geocoder = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA0wuGfkqLD67jR6NfcC8mm4EuUROGis_I&address=%s&sensor=false";
            // Get latitude and longitude of the event address
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
            }

            return $this->render('my/event.html.twig', array(
                'event' => $event,
                'lat' => $latitude,
                'long' => $longitude,
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
            
            date_default_timezone_set("Europe/Paris");
            $event = new Event();
            $em = $this->getDoctrine()->getManager();

            if (!is_null($start) and !is_null($end))
            {
                $event->setStartDate(new \DateTime(date('Y-m-d H:i:s', $start/1000)));
                $event->setEndDate(new \DateTime(date('Y-m-d H:i:s', $end/1000)));
            }

            $form = $this->get('form.factory')->create(EventType::class, $event);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $event->setCreator($user);
                $event->addParticipant($user);
                $em->persist($event);
                $em->flush();

                $manager = $this->get('mgilet.notification');
                $notif=$manager->createNotification($this->generateSubject($event),$event->getName(),'event/'.$event->getId());
                // you can add a notification to a list of entities
                // the third parameter ``$flush`` allows you to directly flush the entities
                $users = $em->getRepository('App:User')->findAll();
                $manager->addNotification(array($this->getUser()), $notif, true);

                $request->getSession()->getFlashBag()->add('notice', 'Evènement créé avec succès !');

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
    public function modifyEvent(Request $request, $id)
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

            $form = $this->get('form.factory')->create(EventType::class, $event);
            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

                $em->persist($event);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Evènement modifié avec succès !');

                return $this->render('my/event.html.twig',array(
                    'event' => $event,
                    'notificationList', $notificationList
                ));
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
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Participation confirmée avec succès !');

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

            $request->getSession()->getFlashBag()->add('notice', 'Participation annulée avec succès !');

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

            // Security check
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                // else error page
                throw new AccessDeniedException('Accès limité aux admins narvaloo.');
            }

            if (!$event) {
                throw $this->createNotFoundException('Aucun event trouvé pour id: ' . $event);
            }

            $em->remove($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Evènement supprimé avec succès !');

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
