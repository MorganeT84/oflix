<?php

namespace App\Controller\Backoffice;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/tvshow')]
class TvShowController extends AbstractController
{
    #[Route('/', name: 'app_backoffice_tv_show_index', methods: ['GET'])]
    public function index(TvShowRepository $tvShowRepository): Response
    {
        return $this->render('backoffice/tv_show/index.html.twig', [
            'tv_shows' => $tvShowRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backoffice_tv_show_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TvShowRepository $tvShowRepository): Response
    {
        $tvShow = new TvShow();
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tvShowRepository->add($tvShow);
            return $this->redirectToRoute('app_backoffice_tv_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/tv_show/new.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backoffice_tv_show_show', methods: ['GET'])]
    public function show(TvShow $tvShow): Response
    {
        return $this->render('backoffice/tv_show/show.html.twig', [
            'tv_show' => $tvShow,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backoffice_tv_show_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TvShow $tvShow, TvShowRepository $tvShowRepository): Response
    {
        $form = $this->createForm(TvShowType::class, $tvShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tvShowRepository->add($tvShow);
            return $this->redirectToRoute('app_backoffice_tv_show_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/tv_show/edit.html.twig', [
            'tv_show' => $tvShow,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backoffice_tv_show_delete', methods: ['POST'])]
    public function delete(Request $request, TvShow $tvShow, TvShowRepository $tvShowRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tvShow->getId(), $request->request->get('_token'))) {
            $tvShowRepository->remove($tvShow);
        }

        return $this->redirectToRoute('app_backoffice_tv_show_index', [], Response::HTTP_SEE_OTHER);
    }
}
