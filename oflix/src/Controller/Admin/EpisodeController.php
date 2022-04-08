<?php

namespace App\Controller\Admin;

use App\Entity\Episode;
use App\Form\EpisodeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/episode', name: 'admin_episode')]
class EpisodeController extends AbstractController
{

    #[Route('/episodes', name: 'browse', methods: ['GET'])]
    public function browse(Request $request): Response
    {
        // on fournit ce formulaire à notre vue
        return $this->render('admin/episode/browse.html.twig', [
        ]);
    }


    #[Route('/add', name: '_add', methods: ['GET', 'POST'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $episode = new Episode();
        $episodeForm = $this->createForm(EpisodeType::class, $episode);

        $episodeForm->handleRequest($request);

        if ($episodeForm->isSubmitted() && $episodeForm->isValid()) {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($episode);
            $entityManager->flush();

            // pour opquast 
            $this->addFlash('success', "L\'épisode `{$episode->getTitle()}` a bien été ajoutée");

            // redirection
            return $this->redirectToRoute('admin_episode_browse');
        }

        return $this->renderForm('admin/episode/add.html.twig', [ 
            'episode_form' => $episodeForm,
        ]);
    }
}
