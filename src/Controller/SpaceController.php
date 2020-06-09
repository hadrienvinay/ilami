<?php
// src/Controller/SpaceController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\SpaceMedia;
use App\Form\SpaceMediaType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SpaceController extends Controller
{
    /**
     * @return type
     */
    public function space()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $spaceMedias = $em->getRepository('App:SpaceMedia')->findAll();

        $spaceMedia = new SpaceMedia();
        $form = $this->get('form.factory')->create(SpaceMediaType::class, $spaceMedia);
            
        return $this->render('space/body.html.twig', array(
            'spaceMedias' => $spaceMedias,
            'spaceMediaForm' => $form->createView(),
            'user' => $user,
        ));
    }
    
    public function addSpaceMedia(Request $request) 
    {
        $spaceMedia = new SpaceMedia();
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(SpaceMediaType::class, $spaceMedia);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $file = $form['picture']->getData();

            if($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('space_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw $this->createNotFoundException(
                        'Erreur lors de l\'import narvaloooo'
                    );
                }
                        
                $spaceMedia->setPicture($newFilename);
                $spaceMedia->setCreatedDate(new \DateTime());
                $spaceMedia->setUpdatedDate(new \DateTime());
                $em->persist($spaceMedia);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Photo Space ajoutée avec succès !');
                return $this->redirectToRoute('space');
            }
            else{
                $request->getSession()->getFlashBag()->add('error', 'Erreur lors de l\'upload');
            }
        }
        
        return $this->render('space/add/space_media.html.twig', array(
            'form' => $form->createView(),
        ));    
       
    }
       
    /**
     * @return Response
     */
    public function deleteSpaceMedia(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $spaceMedia = $em->getRepository('App:SpaceMedia')->find($id);
            // Security check ADMIN and PUBLISHER ONLY
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if(!$this->get('security.authorization_checker')->isGranted('ROLE_PUBLISHER')){
                    throw new AccessDeniedException('Tu ne peux pas supprimer un média, tu n\'as pas les droits !');
                }
            }
            if (!$spaceMedia) {
                throw $this->createNotFoundException('Aucune photo trouvé pour id: ' . $spaceMedia);
            }
            else{
                $em->remove($spaceMedia);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Média Space supprimé avec succès !');
                return $this->redirectToRoute('space');
            }
        }
    }
    
         /**
     * @return Response
     */
    public function updateSpaceMedia(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $spaceMedia = $em->getRepository('App:SpaceMedia')->find($id);
            // Security check
            if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                if(!$this->get('security.authorization_checker')->isGranted('ROLE_PUBLISHER')){
                    throw new AccessDeniedException('Tu ne peux pas modifier un média, tu n\'as pas les droits !');
                }
            }
            if (!$spaceMedia) {
                throw $this->createNotFoundException('Aucune photo trouvé pour id: ' . $spaceMedia);
            }
            else{
                $form = $this->get('form.factory')->create(SpaceMediaType::class, $spaceMedia);
        
                if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                    $file = $form['picture']->getData();

                    if($file){
                        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        // this is needed to safely include the file name as part of the URL
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                        // Move the file to the directory where brochures are stored
                        try {
                            $file->move(
                                $this->getParameter('space_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                            throw $this->createNotFoundException(
                                'Erreur lors de l\'import narvaloooo'
                            );
                        }

                        $spaceMedia->setPicture($newFilename);
                        $spaceMedia->setUpdatedDate(new \DateTime());
                        $em->persist($spaceMedia);
                        $em->flush();

                        $request->getSession()->getFlashBag()->add('success', 'Photo Space modifiée avec succès !');
                        return $this->redirectToRoute('space');
                    }
                    else{
                        $request->getSession()->getFlashBag()->add('error', 'Erreur lors de l\'upload');
                    }
                }
                
            return $this->render('space/modify/space_media.html.twig', array(
                'spaceMedia' => $spaceMedia,
                'form' => $form->createView(),
            ));

            }
        }
    }

}
