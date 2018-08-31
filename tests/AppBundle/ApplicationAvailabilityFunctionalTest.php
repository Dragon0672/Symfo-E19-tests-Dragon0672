<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 30/08/2018
 * Time: 11:48
 */

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * FRONT URLS SUCCESS
     *
     * @dataProvider urlFrontProvider
     */
    public function testFrontPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    //provider des url a tester via l'annotation
    public function urlFrontProvider()
    {
        return array(
            array('/'),
            array('/login'),
            array('/movie/show/the-spectacular-now') // (Si BDD de test générée, penser modifier les slugs des tests afin qu'ils correspondent au nouveau contenu de la BDD ;) )
        );
    }

    /**
     * BACK URLS SUCCESSS (admin)
     *
     * @dataProvider urlBackRoleAdminProvider
     */
    public function testBackRoleAdminPageIsSuccess($url)
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'admin1',
                'PHP_AUTH_PW' => '123'
            )
        );
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlBackRoleAdminProvider()
    {
        return array(
            array('/admin/movie/'),
            array('/admin/movie/the-spectacular-now') // (Si BDD de test générée, penser modifier les slugs des tests afin qu'ils correspondent au nouveau contenu de la BDD ;) )
        );
    }
    /**
     *
     * BACK URLS SUCCESSS (user) - le role user a le droit d'acceder aux pages de consultation
     *
     * @dataProvider urlBackRoleUserProvider
     */
    public function testBackRoleUserPageIsSuccessful($url)
    {
        //je creer mon client avec les données qui vont me permettre de m'authentifier
        // et j'ai changé auparavant dans ma config de test le systeme pour se logger (plus rapide)
        $client = self::createClient(array(), array(
            'PHP_AUTH_USER' => 'user1',
            'PHP_AUTH_PW'   => 'user123',
        ));
        //donnée fournies par les fixtures
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    public function urlBackRoleUserProvider()
    {   //attention a saisir l'url exact (slash de fin etc ...)
        return array(
            array('/admin/movie/'),
            array('/admin/movie/the-spectacular-now') // (Si BDD de test générée, penser modifier les slugs des tests afin qu'ils correspondent au nouveau contenu de la BDD ;) )
        );
    }

    /**
     * BACK URLS FAIL (user) - le role user n'a pas le droit d'acceder aux page d'action
     *
     * @dataProvider urlBackRoleUserForbiddenProvider
     */
    public function testBackRoleUserPageIsForbidden($url)
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'user1',
                'PHP_AUTH_PW' => 'user123'
            )
        );
        $client->request('GET', $url);

        //je verifie que mon utilisateur est interdit (403) sur les pages suivantes
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function urlBackRoleUserForbiddenProvider()
    {
        return array(
            array('/admin/movie/5/edit'), // (Si BDD de test générée, penser modifier les slugs des tests afin qu'ils correspondent au nouveau contenu de la BDD ;) )
            array('/admin/moviecast/new/6'), // (Si BDD de test générée, penser modifier les slugs des tests afin qu'ils correspondent au nouveau contenu de la BDD ;) )
            array('/admin/movie/new'),
        );
    }
}
