<?php

namespace App\Controller\Backoffice;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/character', name: 'backoffice_character_')]
#[IsGranted('ROLE_USER')]
class CharacterController extends AbstractController
{
    #[Route('/', name: 'browse', methods: ['GET'])]
    public function browse(CharacterRepository $characterRepository): Response
    {
        $characterList = $characterRepository->findAll();
        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/character/browse.html.twig', [
            'character_list' => $characterList,
        ]);
    }

    #[Route('/{id}', name: 'read', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function read($id, CharacterRepository $characterRepository): Response
    {
        $characterId = $characterRepository->find($id);
        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/character/read.html.twig', [
            'character' => $characterId,
        ]);
    }

    //todo EDIT BY ID
    #[Route('/{id}/update', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character, ManagerRegistry $doctrine): Response
    {
        $characterForm =  $this->createForm(CharacterType::class, $character);
        $characterForm->handleRequest($request);

        if ($characterForm->isSubmitted() && $characterForm->isValid()) {

            // on ne demande l'entityManager que si on en a besoin
            $entityManager = $doctrine->getManager();

            $entityManager->persist($character);
            $entityManager->flush();

            // pour opquast 
            $this->addFlash('success', "Character `{$character->getFirstName()}` created successfully");

            // redirection
            return $this->redirectToRoute('backoffice_character_browse');
        }

        // on fournit ce formulaire à notre vue
        return $this->render('backoffice/character/add.html.twig', [
            'character_form' => $characterForm->createView(),
            'character' => $character,
            'page' => 'edit',
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $character = new Character();
        // on a créé un formulaire vierge sans données initiales
        $characterForm = $this->createForm(CharacterType::class, $character);

        // Après avoir été affiché le handleRequest nous permettra
        // de faire la différence entre un affichage de formulaire (en GET) 
        // et une soumission de formulaire (en POST)
        // Si un formulaire a été soumis, il rempli l'objet fournit lors de la création
        $characterForm->handleRequest($request);

        // l'objet de formulaire a vérifié si le formulaire a été soumis grace au HandleRequest
        // l'objet de formulaire vérifie si le formulaire est valide (token csrf mais pas que)
        if ($characterForm->isSubmitted() && $characterForm->isValid()) {

            // on ne demande l'entityManager que si on en a besoin
            $entityManager = $doctrine->getManager();

            $entityManager->persist($character);
            $entityManager->flush();

            // pour opquast 
            $this->addFlash('success', "La personnage `{$character->getFirstname()}` a bien été ajoutée");

            // redirection
            return $this->redirectToRoute('backoffice_character_browse');
        }

        // on fournit ce formulaire à notre vue
        return $this->renderForm('backoffice/character/add.html.twig', [
            'character_form' => $characterForm,
            'page' => 'create',
        ]);
    }

    #[Route('/delete/{id}', name: "delete", requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Character $character, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash('success', "character {$character->getId()} deleted");

        $entityManager->remove($character);
        $entityManager->flush();

        // redirection
        return $this->redirectToRoute('backoffice_character_browse');
    }
}
