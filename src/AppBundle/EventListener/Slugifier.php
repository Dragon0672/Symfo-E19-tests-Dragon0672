<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26/08/2018
 * Time: 09:45
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Movie;

class Slugifier
{
    private $slugger;

    public function __construct($slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->setSlug($eventArgs);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->setSlug($eventArgs);
    }

    private function setSlug($eventArgs)
    {
        $entity = $eventArgs->getEntity();

        // only act on some "Movie" entity
        if (!$entity instanceof Movie) {
            return;
        }

        $modifiedSlug = $this->slugger->slugify($entity->getTitle());
        $entity->setSlug($modifiedSlug);
    }
}