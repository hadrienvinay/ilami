<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\SongConverterService;
use App\Form\ConvertorType;
use App\Entity\Song;
use App\Form\SongType;

class MusicController extends Controller
{
    private $song;

    public function __construct(SongConverterService $song)
    {
        $this->song = $song;
    }
    
    /**
     * @Route("/music", name="music")
     */
    public function radio()
    {
        $user=$this->getUser();
        //check if user is connected
        if(!$user){
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);

            $em = $this->getDoctrine()->getManager();
            $songs = $em->getRepository('App:Song')->findAll();

            $song = new Song();
            $form = $this->get('form.factory')->create(SongType::class, $song);
            
            return $this->render('my/music/radio.html.twig', array(
                'notificationList' => $notificationList,
                'songs' => $songs,
                'form' => $form->createView(),
                 ));
            }
    }
    
    
    public function convertor(Request $request)
    {
        $user = $this->getUser();
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
        $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
        $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
       
        $formBuilder = $this->get('form.factory')->createBuilder(ConvertorType::class);  
        
        $form = $formBuilder->getForm();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $songYt = $form['url']->getData();
            $fileName = $this->song->convertSong($request, $songYt,$this->getParameter('download_directory'));
            if(!$fileName){
                return $this->redirectToRoute('convertor');
            }
            $request->getSession()->getFlashBag()->add('success', 'Vidéo convertie avec succès !');
            return $this->file($this->getParameter('download_directory').'/'.$fileName);
        }

        return $this->render('my/music/convertor.html.twig',array(
            'notificationList' => $notificationList,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @return Response
     */
    public function addSong(Request $request)
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
            
            $song = new Song();
            $em = $this->getDoctrine()->getManager();

            $form = $this->get('form.factory')->create(SongType::class, $song);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $songFile = $form['file']->getData();
                $songYt = $form['url']->getData();
                if($songFile){
                    $fileName = $songFile->getClientOriginalName();
                    $songFile->move($this->getParameter('download_directory'),$fileName);
                }else if($songYt){
                    $fileName = $this->song->convertSong($request, $songYt,$this->getParameter('download_directory'));
                    if(!$fileName){
                        return $this->redirectToRoute('radio');
                    }
                }
                else{
                    $request->getSession()->getFlashBag()->add('danger', 'Aucun son fourni en entrée narvalo !');
                    return $this->redirectToRoute('radio');
                }
                $song->setFileName($fileName);
                $song->setTitle("a");
                $song->setArtist("b");
                $song->setUploader($user);
                $song->setCreatedDate(new \DateTime());
                $em->persist($song);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Morceau ajouté avec succès !');
                return $this->redirectToRoute('radio');
            }

            return $this->render('add/song.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function deleteSong(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $song = $em->getRepository('App:Song')->find($id);
            
            if (!$song) {
                throw $this->createNotFoundException('Aucun son trouvé pour id: ' . $id);
            }
            // Security check
            if ($user != $song->getUploader() || !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                // else error page
                throw new AccessDeniedException('Tu ne peux pas supprimer un son que tu n\as pas importé narvalo !');
            }

            $em->remove($song);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Morceau supprimé avec succès !');

            return $this->redirectToRoute('radio');
        }
    }
}
