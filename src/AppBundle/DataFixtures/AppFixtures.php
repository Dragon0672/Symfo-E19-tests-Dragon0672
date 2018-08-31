<?php

namespace AppBundle\DataFixtures;

//par defaut cette classe est use cependant dans la documentation c'est Doctrine\Bundle\FixturesBundle\Fixture qui est utilisée
//use Doctrine\Common\DataFixtures\Fixture;
use AppBundle\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;

use AppBundle\DataFixtures\Faker\MovieAndGenreProvider; //je load mon provider
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Faker;

class AppFixtures extends Fixture{

    private $encoder;
    private $slugger;

    public function __construct(UserPasswordEncoderInterface $encoder, Slugger $slugger)
    {
      $this->encoder = $encoder;
      $this->slugger = $slugger;
    } 

    public function load (ObjectManager $manager){

          // On crée une instance de Faker en français
          $generator = Faker\Factory::create('fr_FR');

          $roleAdmin = new Role();
          $roleAdmin->setCode('ROLE_ADMIN');
          $roleAdmin->setName('Administrateur');

          $roleUser = new Role();
          $roleUser->setCode('ROLE_USER');
          $roleUser->setName('Utilisateur');

          //ROLE_ADMIN

          $userAdmin = new User();
          $userAdmin->setUsername('admin1');
          $userAdmin->setEmail('admin1@oclock.io');
          $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, '123'));
          $userAdmin->setRole($roleAdmin);

          $manager->persist($userAdmin);

          $userAdmin = new User();
          $userAdmin->setUsername('admin2');
          $userAdmin->setEmail('admin2@oclock.io');
          $userAdmin->setPassword($this->encoder->encodePassword($userAdmin, '123'));
          $userAdmin->setRole($roleAdmin);

          $manager->persist($userAdmin);

          //ROLE_USER
          $userUser = new User();
          $userUser->setUsername('user1');
          $userUser->setEmail('user1@oclock.io');
          $userUser->setPassword($this->encoder->encodePassword($userUser, '123'));
          $userUser->setRole($roleUser);

          $manager->persist($userUser);

          $userUser = new User();
          $userUser->setUsername('user2');
          $userUser->setEmail('user2@oclock.io');
          $userUser->setPassword($this->encoder->encodePassword($userUser, '123'));
          $userUser->setRole($roleUser);

          $manager->persist($userUser);

          /*
            Ajout du provider au generator 
            Ceci permet d'ajouter des fonctionnalité de base aux fonctions existante de faker cf doc
          */
         
          $generator->addProvider(new MovieAndGenreProvider($generator));
   
          // On passe le Manager de Doctrine à Faker !
          $populator = new Faker\ORM\Doctrine\Populator($generator, $manager);

          //Movie
          $populator->addEntity('AppBundle\Entity\Movie', 25, array(
              'title' => function() use ($generator) { return $generator->unique()->movieTitle(); },
              'picture' => null,
              'pictureURL' => null
          ),
              array(
                // Ajout d'une fonction de callback suite à la génération du nom du film sur la ligne ci-dessus
                // Donc, possibilité de récupérer l'objet courant, ici un movie avec un titre
                function($movie) { $movie->setSlug($this->slugger->slugify($movie->getTitle())); }
              )
          );

           //Genre
           $populator->addEntity('AppBundle\Entity\Genre', 20, array(
            'name' => function() use ($generator) { return $generator->unique()->movieGenre(); },
          ));

          //Person
          $populator->addEntity('AppBundle\Entity\Person', 20, array(
            'name' => function() use ($generator) { return $generator->name(); },
          ));

           //MovieCast
           $populator->addEntity('AppBundle\Entity\MovieCast', 50, array(
            'orderCredit' => function() use ($generator) { return $generator->numberBetween(1, 10); },
            'role' => function() use ($generator) { return $generator->firstName(); },
          ));

          //Department 
          $populator->addEntity('AppBundle\Entity\Department', 50, array(
            'name' => function() use ($generator) { return $generator->unique()->word(); },
          ));

          //Job 
          $populator->addEntity('AppBundle\Entity\Job', 50, array(
            'name' => function() use ($generator) { return $generator->unique()->jobTitle(); },
          ));

           //MovieCrew 
           $populator->addEntity('AppBundle\Entity\MovieCrew', 200);


          // On demande à Faker d'éxécuter les ajouts
          $insertedEntities = $populator->execute();

          //je recupere les ArrayCollection des objets que j'ai inséré en BDD
          $movieCollection = $insertedEntities['AppBundle\Entity\Movie'];
          $genreCollection = $insertedEntities['AppBundle\Entity\Genre'];
          
          // VERSION sans doublons

          foreach($movieCollection as $movie){

            //Pour etre sure de ne pas avoir de doublon , test avec unique
            shuffle($genreCollection);

            //mes 3 clef etant stockée dans un tableau indexé sur 3 element
            // je recupere successivement mes clef me permettant d'aller recuperer mes données genre
            // grace a la methode de mon entity , j'ajoute un genre a mon filme

            $movie->addGenre($genreCollection[0]);
            $movie->addGenre($genreCollection[1]);
            $movie->addGenre($genreCollection[2]);

            //je prepare / empile la liste de modification a apporté a mes tables
            $manager->persist($movie);
          }

          $manager->flush(); 

          //Je met met en dehors de ma boucle je valide les modification "empilée" a apporter a ma table ici movie
          //$manager->flush($movie);
    }
}