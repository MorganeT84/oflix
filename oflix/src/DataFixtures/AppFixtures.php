<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\TvShow;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager)
    {
        //créer un show
        for ($i = 0; $i < 4; $i++) {
            # code...

            $got = new TvShow();
            // Ces valeurs sont définit par defaut dans la methode construct de entity
            //? $got->setCreatedAt(new DateTimeImmutable());
            //? $got->setNbLikes(0);
            $got->setTitle($this->tvShowTitle());
            $got->setSynopsis('Hackers - Lorem ipsum, dolor sit amet consectetur adipisicing elit. Distinctio labore dolorem dolores molestiae voluptas soluta. Itaque, magnam! Commodi perferendis culpa repellendus, corrupti voluptates amet delectus eaque dolorum, iste voluptatibus veniam quisquam corporis deleniti mollitia odio a ad numquam reprehenderit est cum qui eum. Tenetur reiciendis ipsum, illo quibusdam minus hic?');
            $got->setPublishedAt(new DateTimeImmutable('01-01-01'));

            $entityManager->persist($got);

            //créer des saisons
            $year = 2010;
            $nbSeason = mt_rand(1,5);
            for ($seasonNumber = 1; $seasonNumber <= $nbSeason; $seasonNumber++) {
                $season = new Season();
                $seasonYear = $year + $seasonNumber;
                $season->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
                $season->setSeasonNumber($seasonNumber);
                //associer les saisons au show
                $season->setTvShow(($got));

                $entityManager->persist($season);

                //creer des épisodes
                $nbEpisode = mt_rand(4, 15);
                for ($episodeNumber = 1; $episodeNumber <= $nbEpisode; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setEpisodeNumber($episodeNumber);
                    $episode->setTitle('S0' . $seasonNumber . 'x0' . $episodeNumber);
                    $episode->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
                    //associer des épisodes au saison
                    $episode->setSeason($season);

                    $entityManager->persist($episode);
                }
            }

            //enregistrer le tout en bdd
            $entityManager->flush();
        }
    }

    public function tvShowTitle(): string
    {
        $tvShows = [
            'Atie',
            'Stranger things',
            'Casa de papel',
            'drôle',
            'Malcom',
        ];
        return $tvShows[mt_rand(0, count($tvShows))];
    }
}
