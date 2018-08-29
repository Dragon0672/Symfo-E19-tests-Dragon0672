<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MovieCast
 *
 * @ORM\Table(name="movie_cast")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieCastRepository")
 */
class MovieCast
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
     * @ORM\Column(name="role", type="text")
     */
    private $role;

    /**
     * @var int
     *
     * @ORM\Column(name="orderCredit", type="integer")
     */
    private $orderCredit;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="moviecasts", cascade={"persist"})
     * @var Person
     */
    private $person;

    /**
     * Em complement de l'erreur SQL si movie est vide , je lui rajoute une contrainte 
     * coté formulaire pour eviter de bloquer l'utilisateur sur une erreur SQL
     * 
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="moviecasts", cascade={"persist"})
     * @Assert\NotBlank()
     * @var Movie
     */
    private $movie;
    

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
     * Set role
     *
     * @param string $role
     *
     * @return MovieCast
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set orderCredit
     *
     * @param integer $orderCredit
     *
     * @return MovieCast
     */
    public function setOrderCredit($orderCredit)
    {
        $this->orderCredit = $orderCredit;

        return $this;
    }

    /**
     * Get orderCredit
     *
     * @return int
     */
    public function getOrderCredit()
    {
        return $this->orderCredit;
    }

    /**
     * Get the value of person
     *
     * @return  Person
     */ 
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set the value of person
     *
     * @param  Person  $person
     *
     * @return  self
     */ 
    public function setPerson(Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get the value of movie
     *
     * @return  Movie
     */ 
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set the value of movie
     *
     * @param  Movie  $movie
     *
     * @return  self
     */ 
    public function setMovie(Movie $movie)
    {
        $this->movie = $movie;

        return $this;
    }
}

