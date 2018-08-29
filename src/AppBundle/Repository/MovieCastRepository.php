<?php

namespace AppBundle\Repository;

/**
 * MovieCastRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MovieCastRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * EXO 2
     * 
     * Créer une méthode custom sur MovieCast pour récupérer les moviecasts d'un movie donné.
     *
     * écrire la requête SQL dans PMA pour comprendre l'intérêt d'une telle requête. écrire la requête custom dans le repository. l'utiliser dans 'movie_show'.
     */
    public function findByMovie($movie)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT mc, p 
            FROM AppBundle:MovieCast mc
            JOIN mc.person p
            WHERE mc.movie = :movie
        ')
        ->setParameter('movie', $movie); //comparer un objet suffit

        return $query->getResult(); //renvoit les datas MovieCats et Personnes
    }

    /**
     * EXO 2
     * 
     * Créer une méthode custom sur MovieCast pour récupérer les moviecasts d'un movie donné.
     *
     * écrire la requête SQL dans PMA pour comprendre l'intérêt d'une telle requête. écrire la requête custom dans le repository. l'utiliser dans 'movie_show'.
     */

    public function getMovieCastAndPerson($movie)
    {
       $qb = $this->createQueryBuilder('c')
           ->join('c.person', 'p') //arrive directement a faire la connexion avec ce qui est present dans l'entité ici personne donc je lui impose l'alias p
           ->addSelect('p')
           ->where('c.movie = :myMovie')
           ->setParameter('myMovie', $movie) //je bind le parametre movie
       ;
       
       //je force le type de retour sur un resultat sous forme de tableau
       // possibilité de d'autres format de retour
       return $qb->getQuery()->getArrayResult();
    }
}
