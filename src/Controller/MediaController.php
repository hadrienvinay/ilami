<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Media;
use App\Form\MediaType;

class MediaController extends Controller
{
    
    /**
     * 
     * @return type
     */
    public function media()
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
            $medias = $em->getRepository('App:Media')->findAll();

            $media = new Media();
            $mediaForm = $this->get('form.factory')->create(MediaType::class, $media);
            
            return $this->render('my/media.html.twig', array(
                'medias' => $medias,
                'mediaForm' => $mediaForm->createView(),
                'notificationList' => $notificationList,
            ));
        }
    }
        
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function addMedia(Request $request)
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
            
            $media = new Media();
            $em = $this->getDoctrine()->getManager();
            $form = $this->get('form.factory')->create(MediaType::class, $media);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $media->setUploader($user);
                $media->setCreatedDate(new \DateTime);
                $em->persist($media);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Média ajouté avec succès !');

                return $this->redirectToRoute('media');
            }

            return $this->render('add/media.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function deleteMedia(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $media = $em->getRepository('App:Media')->find($id);
            
            if (!$media) {
                throw $this->createNotFoundException('Aucun média trouvé pour id: ' . $id);
            }
            // Security check
            if ($user != $media->getUploader() || !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                // else error page
                throw new AccessDeniedException('Tu ne peux pas supprimer un média que tu n\as pas importé narvalo !');
            }
            
            $em->remove($media);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Média supprimé avec succès !');

            return $this->redirectToRoute('media');
        }
    }
}
