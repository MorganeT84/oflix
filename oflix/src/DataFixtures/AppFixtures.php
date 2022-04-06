<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\OflixProvider;
use App\Entity\Category;
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
        $faker = Factory::create();
        $faker->addProvider(new OflixProvider($faker));

        $categoryList = [];
        for ($categoryNumber = 0; $categoryNumber < 10; $categoryNumber++) {
            $category = new Category();
            $entityManager->persist($category);

            $category->setName($faker->jobTitle());

            // on rajoute toutes les catégories dans ce tableau
            $categoryList[] = $category;
        }

        //créer un show
        for ($i = 0; $i < 4; $i++) {
            $tvShow = new TvShow();
            // Ces valeurs sont définit par defaut dans la methode construct de entity
            //? $tvShow->setCreatedAt(new DateTimeImmutable());
            //? $tvShow->setNbLikes(0);
             $tvShow->setTitle($faker->unique()->tvShowTitle());
            // avec faker aléatoirement : $tvShow->setTitle($faker->catchPhrase(mt_rand(1, 4), true));
            
            $tvShow->setSynopsis($faker->unique()->realText(200));
            //$tvShow->setPublishedAt(new DateTimeImmutable('01-01-01'));

            //! récupérons jusqu'à 4 catégories au hasard
            $nbCategories = mt_rand(0, 4);
            $categoryForTvShow = $faker->randomElements($categoryList, $nbCategories);

            // créons les associations avec le tvshow actuel
            foreach($categoryForTvShow as $currentCategory)
            {
                $tvShow->addCategory($currentCategory);
            }

            $entityManager->persist($tvShow);

            //! créer des saisons
            $year = 2010;
            $nbSeason = mt_rand(1, 5);
            for ($seasonNumber = 1; $seasonNumber <= $nbSeason; $seasonNumber++) {
                $season = new Season();
                $seasonYear = $year + $seasonNumber;
                $season->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
                $season->setSeasonNumber($seasonNumber);
                //associer les saisons au show
                $season->setTvShow(($tvShow));

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
}
