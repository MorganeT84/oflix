<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\TvShow;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FakeDataMakerController extends AbstractController
{
    #[Route('/fake/data/maker', name: 'app_fake_data_maker')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //créer un show
        $got = new TvShow();
        // Ces valeurs sont définit par defaut dans la methode construct de entity
        //? $got->setCreatedAt(new DateTimeImmutable());
        //? $got->setNbLikes(0);
        $got->setTitle('Game of thrones');
        $got->setSynopsis('Game of thrones - Lorem ipsum, dolor sit amet consectetur adipisicing elit. Distinctio labore dolorem dolores molestiae voluptas soluta. Itaque, magnam! Commodi perferendis culpa repellendus, corrupti voluptates amet delectus eaque dolorum, iste voluptatibus veniam quisquam corporis deleniti mollitia odio a ad numquam reprehenderit est cum qui eum. Tenetur reiciendis ipsum, illo quibusdam minus hic?');
        $got->setPublishedAt(new DateTimeImmutable( '01-01-01'));

        $entityManager->persist($got);

        //créer des saisons
        $year = 2010;
        for ($seasonNumber = 1; $seasonNumber <= 7; $seasonNumber++) {
            $season = new Season();
            $seasonYear = $year + $seasonNumber;
            $season->setPublishedAt(new DateTimeImmutable($seasonYear .'-01-01'));
            $season->setSeasonNumber($seasonNumber);
            //associer les saisons au show
            $season->setTvShow(($got));

            $entityManager->persist($season);

            //creer des épisodes
            for ($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {

                $episode = new Episode();
                $episode->setEpisodeNumber($episodeNumber);
                $episode->setTitle('S0' . $seasonNumber . 'x0' . $episodeNumber);
                $episode->setPublishedAt(new DateTimeImmutable($seasonYear .'-01-01'));
                //associer des épisodes au saison
                $episode->setSeason($season);

                $entityManager->persist($episode);
            }
        }

        //enregistrer le tout en bdd
        $entityManager->flush();

        return $this->render('fake_data_maker/index.html.twig', [
            'controller_name' => 'FakeDataMakerController',
        ]);
    }
}
