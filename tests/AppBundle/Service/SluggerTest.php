<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 29/08/2018
 * Time: 11:42
 */

namespace Tests\AppBundle\Service;

use AppBundle\Service\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase //Test
{
    public function testSlugify()
    {
        // Test avec minuscules
        $slugger = new Slugger();
        $result1 = $slugger->slugify('Hello World'); // Assertion (contrainte) 1
        $result2 = $slugger->slugify('Un éléphant ca Trompe enormement'); // Assertion 2

        $this->assertEquals('hello-world', $result1);
        $this->assertEquals('un-l-phant-ca-trompe-enormement', $result2);

        // Test avec majuscules
        $slugger = new Slugger( false);
        $result3 = $slugger->slugify('Hello World'); // Assertion 3 (failure)

        $this->assertEquals('hello-world', $result3);
    }
}