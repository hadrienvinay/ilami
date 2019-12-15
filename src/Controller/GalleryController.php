<?php
// src/Controller/GalleryController.php
namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GalleryController extends AbstractController
{
    /**
     * @return Response
     */
    public function gallery()
    {
        $em = $this->getDoctrine()->getManager();
        $pictures = $em->getRepository('App:Picture')->findAll();

        return $this->render('my/pic/gallery.html.twig', array(
            'pictures' => $pictures,
        ));
    }

     /**
     * @return Response
     */
    public function showAlbums()
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository('App:Album')->findAll();
        dump($albums);
        return $this->render('my/pic/albums.html.twig', array(
            'albums' => $albums,
        ));
    }
    
     /**
     * @param $id
     * @return Response
     */
    public function showAlbum($id)
    {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('App:Album')->find($id);

        if (!$album) {
            throw $this->createNotFoundException(
                'Pas d\'album trouvé narvaloo pour cet identifiant: '.$id
            );
        }

        return $this->render('my/pic/album.html.twig', array(
            'album' => $album,
        ));
    }

    /**
     * @return Response
     */
    public function addPicture(Request $request)
    {
        $picture = new Picture();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->get('form.factory')->create(PictureType::class, $picture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $file = $form['name']->getData();

            $picture->setDate(new \DateTime);
            $picture->setPublisher($user);
            $em->persist($picture);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Photo ajoutée avec succès !');

            return $this->redirectToRoute('gallery');

        }
        return $this->render('add/picture.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
}
