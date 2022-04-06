<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'browse', methods: ['GET'])]
    public function browse(Request $request): Response
    {
        // on fournit ce formulaire à notre vue
        return $this->render('admin/category/browse.html.twig', [
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $category = new Category();
        // on a créé un formulaire vierge sans données initiales
        $categoryForm = $this->createForm(CategoryType::class, $category);

        // Après avoir été affiché le handleRequest nous permettra
        // de faire la différence entre un affichage de formulaire (en GET) 
        // et une soumission de formulaire (en POST)
        // Si un formulaire a été soumis, il rempli l'objet fournit lors de la création
        $categoryForm->handleRequest($request);

        // l'objet de formulaire a vérifié si le formulaire a été soumis grace au HandleRequest
        // l'objet de formulaire vérifie si le formulaire est valide (token csrf mais pas que)
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            // on ne demande l'entityManager que si on en a besoin
            $entityManager = $doctrine->getManager();

            $entityManager->persist($category);
            $entityManager->flush();

            // pour opquast 
            $this->addFlash('success', "La catégorie `{$category->getName()}` a bien été ajoutée");

            // redirection
            return $this->redirectToRoute('admin_category_browse');
        }

        // on fournit ce formulaire à notre vue
        return $this->renderForm('admin/category/add.html.twig', [
            'category_form' => $categoryForm,
        ]);
    }
}
