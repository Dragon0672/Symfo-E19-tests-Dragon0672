<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 */
class Person
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="MovieCast", mappedBy="person")
     */
    private $moviecasts;

    public function __construct()
    {
        $this->moviecasts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

     /**
     * Get the value of moviecasts
     */ 
    public function getMoviecasts()
    {
        return $this->moviecasts;
    }

     /**
     * Ajoute un moviecast a l'array collection $moviecats
     * sert pour l'affichage et l'enregistrement
     * 
     * L'adder permet de stocker une collection d'objet a enregistrer ou a afficher
     * il n'y a pas de setter car cela doit rester une collection d'objet qui est au passage deja settée a vide dans le constructeur
     * nous auront juste besoin d'ajouter des objet suppplementaire a cette collection (vide)
     * 
     * @param MovieCast $moviecast
     * @return Movie
     */
    public function addMovieCast(MovieCast $moviecast){
        $this->moviecasts[] = $moviecast;

        return $this;
    }

    /*
     * Renvoit la representation chaine de charactere de l'objet si demandé
     * grace a __toString()
     */

    public function __toString(){
        return $this->name;      
    }

}
