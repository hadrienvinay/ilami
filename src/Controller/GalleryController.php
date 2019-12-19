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


class GalleryController extends Controller
{
    /**
     * @return Response
     */
    public function gallery()
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
            $pictures = $em->getRepository('App:Picture')->findAll();

            return $this->render('my/pic/gallery.html.twig', array(
                'pictures' => $pictures,
                'notificationList' => $notificationList
            ));
        }
    }

     /**
     * @return Response
     */
    public function showAlbums()
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
            $albums = $em->getRepository('App:Album')->findAll();
            dump($albums);
            return $this->render('my/pic/albums.html.twig', array(
                'albums' => $albums,
                'notificationList' => $notificationList
            ));
        }
    }
    
     /**
     * @param $id
     * @return Response
     */
    public function showAlbum($id)
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

            return $this->render('my/pic/album.html.twig', array(
                'album' => $album,
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
            
            $picture = new Picture();
            $em = $this->getDoctrine()->getManager();

            $form = $this->get('form.factory')->create(PictureType::class, $picture);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

                $fileName = $form['name']->getData();

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
                            'Erreur lors de l\'import narvaloooo'
                        );
                    }

                }

                $picture->setName($newFilename);
                $picture->setDate(new \DateTime);
                $picture->setPublisher($user);
                $em->persist($picture);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Photo ajoutée avec succès !');

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
                            $this->getParameter('album_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        throw $this->createNotFoundException(
                            'Erreur lors de l\'ajout de l\'album narvaloooo'
                        );
                    }

                    $album->setPresentationPicture($newFilename);   
                }
                else{
                    $album->setPresentationPicture('default.png');
                }
                $album->setUpdatedDate(new \DateTime);
                $em->persist($album);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Album créé avec succès !');

                return $this->redirectToRoute('albums');

            }
                return $this->render('add/album.html.twig', array(
                    'form' => $form->createView(),
                    'notificationList' => $notificationList
            ));
        }
    }

}
