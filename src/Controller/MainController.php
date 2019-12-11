<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @return Response
     * @throws \Exception
     */
    public function index()
    {
        return $this->render('main/body.html.twig', array(
        ));
    }
}
