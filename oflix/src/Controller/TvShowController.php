<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// les annotations de routes sur la classe servent de préfix à toutes les routes définis dans celle-ci
#[Route('/tvshow', name: 'tvshow')]
class TvShowController extends AbstractController
{
    #[Route('/list', name: '_browse')]
    public function browse(TvShowRepository $tvShowRepository): Response
    {
        // recup le tvshow dont id fourni via le paramConverter ou le repository
        $tvShowList =$tvShowRepository->findAll();

        return $this->render('tv_show/browse.html.twig', [
            'tv_show_list' => $tvShowList,
        ]);
    }

    #[Route('/{id}', name: '_read')]
    public function read($id, TvShowRepository $tvShowRepository): Response
    {
        // recup le tvshow dont id fourni via le paramConverter ou le repository
        $tvShow =$tvShowRepository->findOneWithInfosDQL($id);

        return $this->render('tv_show/read.html.twig', [
            'tv_show' => $tvShow,
        ]);
    }
}
