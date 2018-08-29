<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 08/08/2018
 * Time: 14:44
 */

namespace AppBundle\Service;

class Slugger
{
    private $isLower;

    public function __construct( $sluggerToLower )
    {
        $this->isLower = $sluggerToLower;
    }
    
    /**
     * @param $text
     * @return null|string|string[] $string
     */
    public function slugify($textToSlug)
    {
        //si mon parametre de service passé a mon constructeur est true alors je met ma chaine en minuscule
        if ($this->isLower){
            $textToSlug = strtolower(trim(strip_tags($textToSlug)));
        }

        $sluggedText =  preg_replace('/[^a-zA-Z0-9]+(:-[a-zA-Z0-9]+)*/', '-', trim(strip_tags($textToSlug)));

        return $sluggedText;
    }
}