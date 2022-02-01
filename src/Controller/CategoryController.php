<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
 // find and show list of all categories. EACH has the link(path) redirecting to the e.g. category/horror page with containing programs inside.
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route ("/new", name="new")
     */

    public function new(Request $request ): Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        //  Get data from HTTP request - treatment
        $form->handleRequest($request);
        // Was the form submitted?
        if ($form->isSubmitted()) {
            // Deal with the submitted data. For example : persiste & flush the entity
            // Get the Entity Manager ( need to persist the data in data base, that or changes from data base are being persisted
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list.  Redirect to a route that display the result
            return $this->redirectToRoute('category_index');

        }
        // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);

        //treat the form
        //Pour récupérer les informations du formulaire soumis, Symfony a besoin d'aller piocher dans
        // la requête HTTP. Cette requête prend, là aussi, la forme d'un objet.
        //in the top add request




    }

    /**
     * @Route ("/{categoryName}", name="show")
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(
                ['name' => $categoryName,]
            );

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name: '.$categoryName.' found .'
            );
        }
 // bring to page with chosen category name /category/horror and shows there 3 relevant programs (series).
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs,

        ]);
    }


}
