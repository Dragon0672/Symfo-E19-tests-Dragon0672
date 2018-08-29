<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use 
/**
 * MovieCrew
 *
 * @ORM\Table(name="movie_crew")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieCrewRepository")
 */
class MovieCrew
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
     * @ORM\ManyToOne(targetEntity="Person", cascade={"persist"})
     * @var Person
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="Movie", cascade={"persist"})
     *
     * @var Movie
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity="Job", cascade={"persist"})
     *
     * @var Job
     */
    private $job;

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

    /**
     * Get the value of job
     *
     * @return  Job
     */ 
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set the value of job
     *
     * @param  Job  $job
     *
     * @return  self
     */ 
    public function setJob(Job $job)
    {
        $this->job = $job;

        return $this;
    }
}

