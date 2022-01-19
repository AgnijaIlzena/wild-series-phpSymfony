<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends AbstractController
{
    /**
     * @Route("/program/{id}", methods={"GET"}, name="program_index", requirements={"id"="^[1-9]+[0-9]*$"})
     * @return Response
     */
    public function show(int $id): Response
    {
        return $this->render('program/show.html.twig', [
            'website' => 'My first Symfony Project',
            'id' => $id,
                    ]);
    }
}

