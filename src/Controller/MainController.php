<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @return Response
     */
    public function index()
    {
<<<<<<< HEAD
        return $this->render('main/body.html.twig', array(
        ));
=======
        $user = $this->getUser();
        $date = date('Y-m-d');
        if($user!=null) {
            $em = $this->getDoctrine()->getManager();
            $events = $em->getRepository('App:Event')->findByDate($date);

            return $this->render('main/body.html.twig', array(
                'user' => $user,
                'events' => $events,
            ));
        }
        else{
            return $this->redirectToRoute('fos_user_security_login');
        }
>>>>>>> 2be92e5c5f7aebcd89a8204b99a21a31f7e00d4f
    }


}
