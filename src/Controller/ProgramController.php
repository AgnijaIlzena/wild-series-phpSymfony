<?php
// src/Controller/ProgramController.php

namespace App\Controller;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/show/{id<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */

    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id'=>$id]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            //->findAll();
           ->findBy(
               ['program'=> $program],
               ['number' => 'ASC']
             );



        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id: '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
                ]);
    }


    /**
     * @Route("/{programId<^[0-9]+$>}/seasons/{seasonId<^[0-9]+$>}", methods={"GET"}, name="season_show")
     */


   public function showSeason(int $programId, int $seasonId): Response
   {
       $program = $this->getDoctrine()
           ->getRepository(Program::class)
           ->findOneBy(['id' => $programId]);

       $season = $this->getDoctrine()
           ->getRepository(Season::class)
           ->findOneBy(['id' => $seasonId]);

       $episodes = $this->getDoctrine()
           ->getRepository(Episode::class)
           ->findBy(['season' => $season]);

       return $this->render('program/season_show.html.twig', [
           'program' => $program,
           'season' => $season,
           'episodes'=>$episodes,
       ]);
   }

}

