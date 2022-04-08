<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Affiche 3 séries au hasard
     * 
     * @Route("/", name="homepage")
     */
    public function homepage(TvShowRepository $tvShowRepository): Response
    {

        // Les bases de données ne savent pas récupérer des données dans un ordre aléatoire
        // on le fait en PHP
        // on récupère les 50 dernières séries ajoutées
        $allTvShow = $tvShowRepository->findBy([], ['createdAt' => 'DESC'], 100);
        // et on mélange le tableau
        shuffle($allTvShow);

        // Le template n'affichera que 3 éléments
        return $this->render('main/homepage.html.twig', [
            'all_tv_show' => $allTvShow,
        ]);
    }
}
