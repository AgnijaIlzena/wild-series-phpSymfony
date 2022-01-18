<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/app_index/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'message' => 'Bienvenu',
        ]);
    }
}
