<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 23/08/2018
 * Time: 10:27
 */

namespace AppBundle\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $isMaintenance;

    public function __construct()
    {
        // Gestion de l'affichage de la bannière de maintenance
        $this->isMaintenance = true;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        // On teste si une information de maintenance doit être affichée
        // ET si la requête est bien la requête principale (et non une sous-requête).
        if ((!$this->isMaintenance) || (!$event->isMasterRequest())) {
            return;
        }

        $response = $event->getResponse();

        // Je récupère le contenu HTML depuis la réponse
        $originalContent = $response->getContent();

        $maintenanceBanner = "
        <div class='container mt-3'>
            <div class='alert alert-info'>Maintenance prévue prochainement</div>
        </div>
        ";

        $modifiedContent = str_replace('<body>', '<body>' . $maintenanceBanner, $originalContent);

        $response->setContent( $modifiedContent );
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}