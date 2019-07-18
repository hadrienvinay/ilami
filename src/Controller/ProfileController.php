<?php
// src/Controller/ProfileController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @return Response
     */
    public function profil()
    {
        $user = $this->getUser();

        return $this->render('my/profil.html.twig', array(
            'user' => $user,
        ));
    }
    /**
     * @return Response
     * @throws \Exception
     */
    public function modifyProfil()
    {
        $user = $this->getUser();

        return $this->render('my/profil.html.twig', array(
            'user' => $user,
            ));
    }

}
