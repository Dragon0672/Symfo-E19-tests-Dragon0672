<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Job
 *
 * @ORM\Table(name="job")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobRepository")
 */
class Job
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
     * @ORM\Column(name="name", type="string", length=70)
     */
    private $name;

    /**
     * Relation unidirectionelle => inversedBy non obligatoire
     * 
     * @ORM\ManyToOne(targetEntity="Department")
     * 
     * Permet si besoin de garder une association avec une foreign key a vide 
     *  cf option BDD onDelete mysql
     * 
     * @ORM\JoinColumn(onDelete="SET NULL")
     * 
     * @var Department
     */
    private $department;


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
     * @return Job
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
     *
     * @return  Department
     */ 
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     *
     * @param  Department
     *
     * @return  self
     */ 
    public function setDepartment(Department $department)
    {
        $this->department = $department;

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

