<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/category', name: 'backoffice_categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: '_browse', methods: ['GET'])]
    public function browse(CategoryRepository $categoriesRepository): Response
    {
        $categoriesList = $categoriesRepository->findAll();
        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/category/browse.html.twig', [
            'categories_list' => $categoriesList,
        ]);
    }

    #[Route('/{id}', name: 'read', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function read($id, CategoryRepository $categoriesRepository): Response
    {
        $categoryId = $categoriesRepository->find($id);
        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/category/read.html.twig', [
            'category' => $categoryId,
        ]);
    }

    //todo EDIT BY ID
    #[Route('/{id}/update', name: '_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request , Category $category, ManagerRegistry $doctrine): Response
    {
        $categoryForm =  $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            // on ne demande l'entityManager que si on en a besoin
            $entityManager = $doctrine->getManager();
            
            $category->setUpdatedAt(new DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', "Category `{$category->getName()}` udpated successfully");

            return $this->redirectToRoute('backoffice_category_browse');
        }

        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/category/add.html.twig', [
            'category_form' => $categoryForm->createView(),
            'category' => $category,
            'page' => 'edit',
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
            return $this->redirectToRoute('backoffice_categories_browse');
        }

        // on fournit ce formulaire à notre vue
        return $this->renderForm('backoffice/category/add.html.twig', [
            'category_form' => $categoryForm,
            'page' => 'create',
        ]);
    }

    #[Route('/delete/{id}', name:"delete", requirements: ['id' => '\d+'], methods: ['GET'] )]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash('success', "Category {$category->getId()} deleted");

        $entityManager->remove($category);
        $entityManager->flush();

         // redirection
         return $this->redirectToRoute('backoffice_categories_browse');
    }
}
