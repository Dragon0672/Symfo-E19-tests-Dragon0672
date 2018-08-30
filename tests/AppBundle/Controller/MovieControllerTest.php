<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    // Tips : Liste de film qui distingue ma page d'une autre page en front
    public function testHomepage()
    {
        //on creer un navigateur
        $client = static::createClient();

        //j'accede a mon navigateur sur ma hompegaepage par le biais de l'url racine /
        $crawler = $client->request('GET', '/');

        //je m'assure que ma page ai bien un code de succès pour continuer mes tests
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //je peux tester via la fonctionnalité html:contains que mon HTMl contient bien le texte Liste de film
        $this->assertContains(
            'Liste de films',
                    $crawler->filter('html:contains("Liste de films")')->text());

        //je peux tester via selecteur CSS le contenu d'une balise ou d'un element ici on recherche le title
        $this->assertContains(
            'Liste de films',
                    $crawler->filter('.container .row .col h1')->text());
        ;
    }

    public function testMoviepage()
    {
        //on creer un navigateur
        $client = static::createClient();

        //j'accede a mon navigateur sur ma hompegaepage par le biais de l'url racine /
        $crawler = $client->request('GET', '/');

        //je m'assure que ma page ai bien un code de succès pour continuer mes tests
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler
            ->filter('.container .row .col .row .col-4:first-child a')->link();

        // and click it
        $crawler = $client->click($link);

// print_r($crawler->filter('h2'));
        $this->assertContains(
            'Acteurs',
            $crawler->filter('h2')->eq(1)->text());
            // $crawler->filter('h2 ~ h2')->text());
    }
}
