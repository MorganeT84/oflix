<?php


// src/EventListener/DatabaseActivitySubscriber.php
namespace App\EventSubscriber;

use App\Entity\TvShow;
use App\Utils\Slugger;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class SluggerSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(Slugger $slugger) {
        $this->slugger = $slugger;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function prePersist(LifecycleEventArgs $args): void
    {
        // on récupère le tvshow avant sa mise à jour
        $tvShow = $args->getObject();
        // Si l'objet récupéré n'est pas de la classe tvshow alors on ne fait rien
        if (!$tvShow instanceof TvShow) {
            return;
        }
        
        // on slugifie le titre
        $slug = $this->slugger->makeSlug($tvShow->getTitle());

        // et on met à jour l'objet
        $tvShow->setSlug($slug);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        // on récupère le tvshow avant sa mise à jour
        $tvShow = $args->getObject();

        // Si l'objet récupéré n'est pas de la classe tvshow alors on ne fait rien
        if (!$tvShow instanceof TvShow) {
            return;
        }

        // on slugifie le titre
        $slug = $this->slugger->makeSlug($tvShow->getTitle());

        // et on met à jour l'objet
        $tvShow->setSlug($slug);
        
        // dd($args, $tvShow);
        // $this->logActivity('update', $args);
    }

}
