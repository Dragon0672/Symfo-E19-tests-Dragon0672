<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\MovieCast;
use AppBundle\Service\Slugger;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Movie
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

     /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/gif",})
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_url", type="string", length=255, nullable=true)
     */
    private $pictureURL;

    /**
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="movies", cascade={"persist"})
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity="MovieCast", mappedBy="movie")
     */
    private $moviecasts;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $slug
     * @return Movie
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $pictureURL
     */
    public function setPictureURL($pictureURL)
    {
        $this->pictureURL = $pictureURL;
    }

    /**
     * @return string
     */
    public function getPictureURL()
    {
        return $this->pictureURL;
    }


    public function addGenre(Genre $genre)
    {
        // On informe l'objet $person que cet objet movie lui est associé
        $genre->addMovie($this);
        // On ajoute l'objet $genre à la liste
        $this->genres[] = $genre;
    }

    /**
     * Get the value of moviecasts
     */ 
    public function getMovieCasts()
    {
        return $this->moviecasts;
    }

    /**
     * Ajoute un moviecast a l'array collection $moviecats
     * sert pour l'affichage et l'enregistrement
     * 
     * @param MovieCast $moviecast
     * @return Movie
     */
    public function addMovieCast(MovieCast $moviecast){
        $this->moviecasts[] = $moviecast;

        return $this;
    }

    /**
     * Get the value of genres
     */ 
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function applySlug()
    {
        /*$slugger = new Slugger( true );

        $sluggedTitle = $slugger->slugify( $this->getTitle() );

        $this->setSlug($sluggedTitle);*/
    }

    /*
     * Renvoit la representation chaine de charactere de l'objet si demandé
     * grace a __toString()
     */
    public function __toString(){
        return $this->title;
    }
}
