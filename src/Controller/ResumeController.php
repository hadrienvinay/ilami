<?php
// src/Controller/ResumeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumeController extends AbstractController
{
    /**
     * @return Response
     */
    public function resume()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findAll();
        $events = $em->getRepository('App:Event')->findAll();

        return $this->render('my/resume.html.twig', array(
            'users' => $users,
            'events' => $events,
        ));
    }

}
