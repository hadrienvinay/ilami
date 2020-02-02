<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $user=$this->getUser();
        //check if user is connected
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException('Tu n\'es pas un admin narvalo !');
        }
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
        $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
        $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
        
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository('App:User');
        $users = $usersRepo->findAll();
        
        return $this->render('admin/index.html.twig', array(
            'users' => $users,
            'notificationList' => $notificationList,
        ));
    }
    
    public function changePassword(Request $request)
    {
        $user=$this->getUser();
        //check if user is connected
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException('Tu n\'es pas un admin narvalo !');
        }
        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $id = $request->request->get('userId');
            $em = $this->getDoctrine()->getManager();
            $selectedUser = $em->getRepository('App:User')->find($id);
            $selectedUser->setPassword($this->encoder->encodePassword($selectedUser,$password));
            
            $em->persist($selectedUser);
            $em->flush();
                        
            $request->getSession()->getFlashBag()->add('success', 'Mot de passe de '.$selectedUser->getUsername().' modifié avec succès !');
        }
        return $this->redirectToRoute('admin');
    }
}
