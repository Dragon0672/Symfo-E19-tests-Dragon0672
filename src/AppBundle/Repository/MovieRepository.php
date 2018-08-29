<?php

namespace AppBundle\Repository;

class MovieRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * EXO 1
     * 
     * DQL
     * 
     * Créer une méthode custom sur Movie pour récupérer la liste les films par ordre alphabétique / via DQL ou QueryBuilder.
     *
     * écrire la requête custom dans le repository. l'utiliser dans 'homepage'.
     */

     public function findAllDQLOrderedByName(){

        $movies = $this->getEntityManager()
                        ->createQuery('
                        SELECT m 
                        FROM AppBundle:Movie m 
                        ORDER BY m.title ASC
                  ');
                  
        return $movies->getResult();
     }

      /**
     * EXO 1
     * 
     * DQL
     * 
     * Créer une méthode custom sur Movie pour récupérer la liste les films par ordre alphabétique / via DQL ou QueryBuilder.
     *
     * écrire la requête custom dans le repository. l'utiliser dans 'homepage'.
     */

    public function findAllQueryBuilderOrderedByName(){

        $movies = $this->createQueryBuilder('m')
                       ->orderBy('m.title', 'ASC');
        
                  
        return $movies->getQuery()->getResult();
     }
}
