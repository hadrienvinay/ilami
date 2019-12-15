<?php
// src/Controller/EventController.php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EventController extends AbstractController
{
    /**
     * @return Response
     */
    public function event($id)
    {
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
            'long' => $longitude
        ));
    }

    /**
     * @return Response
     */
    public function addevent(Request $request)
    {
        $event = new Event();
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create(EventType::class, $event);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->persist($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Evènement créé avec succès !');

            return $this->redirectToRoute('resume');

        }
            return $this->render('add/event.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     */
    public function modifyEvent(Request $request, $id)
    {
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
                'event' => $event
            ));
        }

        return $this->render('modify/event.html.twig', array(
            'event' => $event,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @return Response
     */
    public function deleteEvent($id)
    {
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
