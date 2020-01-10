<?php
// src/Controller/ProfilController.php
namespace App\Controller;

use App\Form\UserType;
use App\Form\RecommandationType;
use App\Service\GeocoderService;
use App\Entity\ProfilePicture;
use App\Entity\Recommandation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;



class ProfilController extends Controller
{
    /**
     * @return Response
     */
    public function profil($id)
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
            $user = $em->getRepository('App:User')->find($id);
            if (!$user) {
                throw $this->createNotFoundException(
                    'Pas d\'utilisateur trouvé narvaloo pour cet identifiant: '.$id
                );
            }
            return $this->render('my/profil.html.twig', array(
                'user' => $user,
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function modifyProfil(Request $request, $id, GeocoderService $geocoder)
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
            $user = $em->getRepository('App:User')->find($id);
            $prevAddress = $user->getAddress();

            if($user != $this->getUser()){
                throw new AccessDeniedException('Tu peux pas modifier un autre profil que le tien narvaloo !');
            }

            $form = $this->get('form.factory')->create(UserType::class, $user);
            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                if ($prevAddress != $user->getAddress()){
                    $pos = $geocoder->convertAddress($user->getAddress());
                    $user->setLatitude($pos[0]);
                    $user->setLongitude($pos[1]);
                }
                $user->setUpdatedDate(new \DateTime);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Profil modifié avec succès !');

                return $this->redirectToRoute('profile',array('id'=>$user->getId()));
            }

            return $this->render('modify/profil.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function addRecommandation(Request $request, GeocoderService $geocoder)
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
            
            $recommandation = new Recommandation();
            $em = $this->getDoctrine()->getManager();

            $form = $this->get('form.factory')->create(RecommandationType::class, $recommandation);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pos = $geocoder->convertAddress($recommandation->getAddress());
                $recommandation->setLatitude($pos[0]);
                $recommandation->setLongitude($pos[1]);
                $recommandation->setUser($user);
                $em->persist($recommandation);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Recommandation ajoutée avec succès !');

                return $this->render('my/profil.html.twig',array(
                    'user' => $user,
                    'notificationList' => $notificationList
                ));
            }

            return $this->render('add/recommandation.html.twig', array(
                'user' => $user,
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }


    /** NOT WORKING  TO SET UP
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function modifyProfilePicture(Request $request, $id)
    {
  
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);

        if($user != $this->getUser()){
            throw new AccessDeniedException('Tu peux pas modifier une autre photo de profil que le tien narvalooo !');
        }

        $profilePic = new ProfilePicture();
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->createBuilder(FormType::class, $profilePic)
            ->add('path',   FileType::class, array(
                'required' => true,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ))
            ->getForm();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $file = $form['path']->getData();
            if ($file) {

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('profile_pic_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $profilePic->setName($newFilename);
                $profilePic->setDate(new \DateTime());
                $profilePic->setUser($user);
                $user->setProfilePicture($profilePic);
                $em->persist($profilePic);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Photo modifiée avec succès !');

                return $this->render('my/profil.html.twig', array(
                    'user' => $user
                ));
            }
        }

        return $this->render('modify/profilPicture.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));

    }

}
