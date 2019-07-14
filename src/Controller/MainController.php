<?php
// src/Controller/MainController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /***
     * @return Response
     * @throws \Exception
     */
    public function index()
    {
        $number = random_int(0, 100);

        return $this->render('base.html.twig', [
            'number' => $number,
        ]);
    }

    /***
     * @return Response
     * @throws \Exception
     */
    public function index2()
    {
        return $this->render('base.html.twig', [
        ]);
    }
}
