<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CatanController extends Controller
{
    /**
     * @Route("/catan", name="catan")
     */
    public function catan()
    {
        $user = $this->getUser();
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
        $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
        $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
        
        return $this->render('catan/index.html.twig', array(
            'notificationList' => $notificationList,
        ));
    }
}
