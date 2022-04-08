<?php

//src/DataFixtures/MyCustomNativeLoader.php

namespace App\DataFixtures\Loader;

use App\DataFixtures\Provider\OflixProvider;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;


class NelmioCustomLoader extends NativeLoader
{
    // on surcharge la méthode par défaut qui créé le générateur de faker
    // pour pouvoir ajouter les loader que l'on souhaite
    protected function createFakerGenerato(): FakerGenerator
    {
        $generator = FakerGeneratorFactory::create(parent::LOCALE);
        $generator->addProvider(new AliceProvider());

        //ajout du nouveau provider en passant le generator dans le constructeur de notre classe (heritée du parent base)
        $generator->addProvider(new OflixProvider($generator));
        $generator->seed($this->getSeed());

        return $generator;
    }
}