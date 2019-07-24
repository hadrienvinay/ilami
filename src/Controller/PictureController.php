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
