<?php 

namespace App\DataFixtures\Provider;

class OflixProvider extends \Faker\Provider\Base {

    /**
     * returns a random tv show title
     *
     * @return string
     */
    public function tvShowTitle() :string {
        $tvShows = [
            'Mr Robot',
            'Breaking Bad',
            'Friends',
            'Game Of Throne',
            'Son of Anarchy',
            'Malcom',
        ];

        // renvoie un nom de tv show au hasard dans le tableau ci dessus grace à mt_rand()
        return $tvShows[mt_rand(0, count($tvShows) - 1)];
    }
}