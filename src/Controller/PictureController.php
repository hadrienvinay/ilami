<?php
// src/Controller/PictureController.php
namespace App\Controller;

use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureController extends AbstractController
{
    /**
     * @return Response
     */
    public function showPictures()
    {
        $em = $this->getDoctrine()->getManager();
        //order by date
        $pictures = $em->getRepository('App:Picture')->findBy(array(), array('date' => 'desc'));

        return $this->render('my/pictures.html.twig', array(
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

        return $this->render('my/albums.html.twig', array(
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

        return $this->render('my/album.html.twig', array(
            'album' => $album,
        ));
    }

    /**
     * @return Response
     */
    public function addPicture(Request $request)
    {
        $picture = new Picture();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $events = $em->getRepository('App:Event')->findAll();
        $form = $this->get('form.factory')->create(PictureType::class, $picture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $em->persist($picture);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Photo ajoutée avec succès !');

            return $this->redirectToRoute('resume');

        }
        return $this->render('add/picture.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
