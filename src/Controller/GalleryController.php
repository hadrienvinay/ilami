<?php
// src/Controller/GalleryController.php
namespace App\Controller;

use App\Entity\Picture;
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

        return $this->render('my/gallery.html.twig', array(
            'pictures' => $pictures,
        ));
    }

    /**
     * @return Response
     */
    public function addpicture(Request $request)
    {
        $event = new Picture();
        $em = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->create(PictureType::class, $event);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->persist($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Photo ajoutée avec succès !');

            return $this->redirectToRoute('gallery');

        }
            return $this->render('add/picture.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
}
