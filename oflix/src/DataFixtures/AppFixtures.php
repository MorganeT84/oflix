<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\OflixProvider;
use App\Entity\Category;
use App\Entity\Character;
use App\Entity\Episode;
use App\Entity\RolePlay;
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

        $characterList = [];
        for ($characterNumber = 0; $characterNumber < 100; $characterNumber++) {
            $character = new Character();
            $entityManager->persist($character);

            $gender = $faker->randomElement([Character::GENDER_MALE, Character::GENDER_FEMALE]);
            $character->setGender($gender);
            if ($gender === Character::GENDER_MALE) {
                $character->setFirstName($faker->firstNameMale());
            } else {
                $character->setFirstName($faker->firstNameFemale());
            }
            $character->setLastName($faker->lastName());
            $character->setBio($faker->realText(100));
            $character->setAge( $faker->numberBetween(5, 150));

            // on rajoute toutes les catégories dans ce tableau
            $characterList[] = $character;
        }

        // créer un  show
        for ($i = 1; $i < 5; $i++) {
            $tvShow = new TvShow();
            $entityManager->persist($tvShow);
            $title = $faker->unique()->tvShowTitle();
            $tvShow->setTitle($title);
            $imageId = $faker->numberBetween(1, 500);
            $tvShow->setSynopsis($faker->realText(200));
            $tvShow->setNbLikes($faker->randomNumber(5));
            $tvShow->setImage("https://picsum.photos/id/{$imageId}/200/300");
            
            
            // récupérons jusqu'à 4 catégories au hasard
            $nbCategories = $faker->numberBetween(0, 4);
            $categoryForTvShow = $faker->randomElements($categoryList, $nbCategories);

            // créons les associations avec le tvshow actuel
            foreach($categoryForTvShow as $currentCategory)
            {
                $tvShow->addCategory($currentCategory);
            }

            // récupérons jusqu'à 25 personnages au hasard
            $nbCharacters = $faker->numberBetween(5, 25);
            $characterForTvShow = $faker->randomElements($characterList, $nbCharacters);

            // créons les associations avec le tvshow actuel
            $creditOrder = 1;
            foreach($characterForTvShow as $currentCharacter)
            {
                $rolePlay = new RolePlay();
                $entityManager->persist($rolePlay);

                $rolePlay->setPersonage($currentCharacter);
                $rolePlay->setTvshow($tvShow);
                $rolePlay->setCreditOrder($creditOrder);
                $creditOrder++;
            }

            // créer des saisons
            $year = $faker->dateTimeThisCentury()->format('Y');
            $nbSeason = $faker->numberBetween(1, 10);
            for ($seasonNumber = 1; $seasonNumber <= $nbSeason; $seasonNumber++) {
                $season = new Season();
                $entityManager->persist($season);

                $seasonYear = $year + $seasonNumber;
                $season->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
                $season->setSeasonNumber($seasonNumber);
                // associer les saisons au show
                $season->setTvShow($tvShow);

                // pour la saison actuelle, on crée des épisodes
                $nbEpisodes = $faker->numberBetween(4, 15);
                for ($episodeNumber = 1; $episodeNumber <= $nbEpisodes; $episodeNumber++) {
                    $episode = new Episode();
                    $entityManager->persist($episode);

                    $episode->setEpisodeNumber($episodeNumber);
                    $episodeTitle = $tvShow->getTitle() . ' - S0' . $seasonNumber . 'x0' . $episodeNumber;
                    if ($episodeNumber >= 10) {
                        $episodeTitle = $tvShow->getTitle() . ' - S0' . $seasonNumber . 'x' . $episodeNumber;
                    }
                    $episode->setTitle($episodeTitle);
                    // associer les épisodes aux saisons
                    $episode->setSeason($season);
                }
            }
        }

        // enregistrer le tout en BDD
        $entityManager->flush();

    }

}
