<?php
// src/Controller/CalendarController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @return Response
     * @throws \Exception
     */
    public function calendar()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findAll();
        $events = $em->getRepository('App:Event')->findAll();

        return $this->render('my/calendar.html.twig', [
            'users' => $users,
            'events' => $events,
        ]);
    }
}
