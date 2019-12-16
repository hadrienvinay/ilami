<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\ProfilePicture;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;


class ProfileController extends AbstractController
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
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->find($id);
            if (!$user) {
                throw $this->createNotFoundException(
                    'Pas d\'utilisateur trouvé narvaloo pour cet identifiant: '.$id
                );
            }
            return $this->render('my/profil.html.twig', array(
                'user' => $user,
            ));
        }
    }
    /**
     * @return Response
     */
    public function modifyProfil(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->find($id);

            if($user != $this->getUser()){
                throw new AccessDeniedException('Tu peux pas modifier un autre profil que le tien narvaloo !');
            }

            $form = $this->get('form.factory')->create(UserType::class, $user);
            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Profil modifié avec succès !');

                return $this->render('my/profil.html.twig',array(
                    'user' => $user
                ));
            }

            return $this->render('modify/profil.html.twig', array(
                'user' => $user,
                'form' => $form->createView()
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
