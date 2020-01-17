<?php
// src/Controller/GalleryController.php
namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Album;
use App\Form\AlbumType;
use App\Form\PictureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



class GalleryController extends Controller
{
    /**
     * @return Response
     */
    public function gallery(Request $request, PaginatorInterface $paginator)
    {
        //check if user is connected
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            if($request->query->get('type')=="album"){
                $active = 0;
            }else {
                $active = 1;
            }
            $em = $this->getDoctrine()->getManager();
            $pictures = $em->getRepository('App:Picture')->findAll();
            $albums = $em->getRepository('App:Album')->findAll();

            $picturesPage = $paginator->paginate(
                $pictures, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                40 // Nombre de résultats par page
            );

            //picture form
            $picture = new Picture();
            $pictureForm = $this->get('form.factory')->create(PictureType::class, $picture);
            //album form
            $album = new Album();
            $albumForm = $this->get('form.factory')->create(AlbumType::class, $album);
            
            return $this->render('my/pic/gallery.html.twig', array(
                'picturesPage' => $picturesPage,
                'albums' => $albums,
                'active' => $active,
                'pictureForm' => $pictureForm->createView(),
                'albumForm' => $albumForm->createView(),
                'notificationList' => $notificationList
            ));
        }
    }

     /**
     * @param $id
     * @return Response
     */
    public function showAlbum($id,Request $request, PaginatorInterface $paginator)
    {
        //check if user is connected
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $album = $em->getRepository('App:Album')->find($id);

            if (!$album) {
                throw $this->createNotFoundException(
                    'Pas d\'album trouvé narvaloo pour cet identifiant: '.$id
                );
            }
            
            //picture form
            $picture = new Picture();
            $picture->setAlbum($album);
            $pictureForm = $this->get('form.factory')->create(PictureType::class, $picture);

            return $this->render('my/pic/album.html.twig', array(
                'album' => $album,
                'pictureForm' => $pictureForm->createView(),
                'notificationList' => $notificationList
            ));
        }
    }

    /**
     * @return Response
     */
    public function addPicture(Request $request)
    {
        //check if user is connected
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $em = $this->getDoctrine()->getManager();
            $id = $request->query->get('id');
            if($id){
                $album = $em->getRepository('App:Album')->find($id);
            } 
            else{ $album = null;}

            $picture = new Picture();
            
            if($album){
                $picture->setAlbum($album);
            }
            
            $form = $this->get('form.factory')->create(PictureType::class, $picture);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

                $file = $form['files']->getData();

                if($file){
                    //foreach ($fileNames as $file){                
                        //if($file){
                            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            // this is needed to safely include the file name as part of the URL
                            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                            // Move the file to the directory where brochures are stored
                            try {
                                $file->move(
                                    $this->getParameter('pictures_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e) {
                                // ... handle exception if something happens during file upload
                                throw $this->createNotFoundException(
                                    'Erreur lors de l\'import narvaloooo'
                                );
                            }
                            $picture->setFileName($newFilename);
                            $picture->setCreatedDate(new \DateTime);
                            $picture->setPublisher($user);
                            $user->addPicture($picture);
                            $em->persist($picture);
                            $em->persist($user);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add('success', 'Photo ajoutée avec succès !');
                        //}
                   // }
                }
                return $this->redirectToRoute('gallery');
            }
            return $this->render('add/picture.html.twig', array(
                'form' => $form->createView(),
                'notificationList' => $notificationList
            ));
        }
    }
    
    /**
     * @return Response
     */
    public function addAlbum(Request $request)
    {
        //check if user is connected
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
            $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
            $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
            $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
            $album = new Album();
            $em = $this->getDoctrine()->getManager();

            $form = $this->get('form.factory')->create(AlbumType::class, $album);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $fileName = $form['presentationPicture']->getData();

                if($fileName){
                    $originalFilename = pathinfo($fileName->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$fileName->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $fileName->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        throw $this->createNotFoundException(
                            'Erreur lors de l\'ajout de l\'album narvaloooo'
                        );
                    }

                    $album->setPresentationPicture($newFilename);
                    $picture = new Picture();
                    $picture->setAlbum($album);
                    $picture->setFileName($newFilename);
                    $picture->setCreatedDate(new \DateTime);
                    $picture->setPublisher($user);
                    $user->addPicture($picture);
                    $em->persist($picture);
                }
                else{
                    $album->setPresentationPicture('default.png');
                }
                $album->setCreator($user);
                $album->setUpdatedDate(new \DateTime);
                $user->setAlbumCreated($album->getId());
                $em->persist($album);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Album créé avec succès !');

                return $this->redirectToRoute('album',array('id'=>$album->getId()));
            }
                return $this->render('add/album.html.twig', array(
                    'form' => $form->createView(),
                    'notificationList' => $notificationList
            ));
        }
    }
    
     /**
     * @return Response
     */
    public function deleteAlbum(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $album = $em->getRepository('App:Album')->find($id);
            // Security check
            if ($user != $album->getCreator() || !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException('Tu ne peux pas supprimer un album que tu n\as pas crée narvalo !');
            }
            if (!$album) {
                throw $this->createNotFoundException('Aucune photo trouvé pour id: ' . $album);
            }
            else{
                $em->remove($album);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Album supprimé avec succès !');
                return $this->redirectToRoute('gallery');
            }
        }
    }
    
    /**
     * @return Response
     */
    public function deletePicture(Request $request, $id)
    {
        //check if user is connected
        $user=$this->getUser();
        if(!$user) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        else{
            $em = $this->getDoctrine()->getManager();
            $picture = $em->getRepository('App:Picture')->find($id);
            // Security check
            if ($user != $picture->getPublisher() || !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException('Tu ne peux pas supprimer une photo que tu n\as pas importée narvalo !');
            }
            if (!$picture) {
                throw $this->createNotFoundException('Aucune photo trouvé pour id: ' . $picture);
            }
            else{
                $em->remove($picture);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Photo supprimée avec succès !');
                return $this->redirectToRoute('gallery');
            }
        }
    }

}
