<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Service\GeocoderService;
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
            
            return $this->render('my/event.html.twig', array(
                'event' => $event,
                'notificationList' => $notificationList
            ));
        }
    }

    /**
     * @return Response
     */
    public function addevent(Request $request, $start, $end, GeocoderService $geocoder)
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
                //set lat and long
                $pos = $geocoder->convertAddress($event->getAddress());
                $event->setUpdatedDate(new \DateTime);
                $event->setLatitude($pos[0]);
                $event->setLongitude($pos[1]);
                $event->setCreator($user);
                $event->addParticipant($user);
                $em->persist($event);
                $em->flush();

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
                    $pos = $geocoder->convertAddress($event->getAddress());
                    $event->setLatitude($pos[0]);
                    $event->setLongitude($pos[1]);
                }
                $event->setUpdatedDate(new \DateTime);
                $em->persist($event);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Evènement modifié avec succès !');

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



}
