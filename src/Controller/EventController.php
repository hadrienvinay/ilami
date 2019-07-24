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

class EventController extends AbstractController
{
    /**
     * @return Response
     */
    public function event($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('App:Event')->find($id);
        return $this->render('my/event.html.twig', array(
            'event' => $event,
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



}
