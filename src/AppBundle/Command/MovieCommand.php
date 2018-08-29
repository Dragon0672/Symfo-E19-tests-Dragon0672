<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 26/08/2018
 * Time: 15:57
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/*use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;*/
use Symfony\Bridge\Doctrine\RegistryInterface as Doctrine;
use AppBundle\Entity\Movie;

class MovieCommand extends Command
{
    private $doctrine;

    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }

    //configuration de la commande
    protected function configure()
    {
        $this
            ->setName('app:movie:image')
            ->setDescription('Imports a new movie to database')
            /*->addArgument(
                'username',
                InputArgument::REQUIRED,
                'The username of the user.'
            )
            ->addOption(
                'username',
                'u',
                InputOption::VALUE_REQUIRED,
                'Name of the user',
                1
            )*/
            ->setHelp('This command allows you to get movie images by API');
    }

    //logique de la commande
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Movie picture import',
            '============',
            '',
        ]);

        $em = $this->doctrine->getManager();

        //je recupere tout mes film à mettre a jour
        $movies = $this->doctrine->getRepository(Movie::class)->findAll();
        foreach ($movies as $movie){
            $output->writeln('Traitement de : ' . $movie->getTitle());
            //execute une requete HTTP avec retour
            $omDBResult = $this->getCurl( $movie->getTitle() );

            //si je n'ai pas eu d'erreur sur mon retour
            if ($omDBResult) {
                //$omDBResult contient un objet stdclass
                $movie->setPictureURL($omDBResult->Poster);
            }
        }

        $em->flush();

        $output->writeln([
            'Finish !',
            '============',
            '',
        ]);
    }

    private function getCurl($title)
    {
        // http://www.omdbapi.com/?t=andre&apikey=55429286
        // http://php.net/manual/fr/function.urlencode.php

        //si erreur curl_init : sudo apt-get install php-curl
        //Exemple : http://www.omdbapi.com/?t=andre&apikey=55429286
        //Encoder une url : http://php.net/manual/fr/function.urlencode.php
        // url encode permet de pallier au probleme d'espace et accents qui pourait corrompre l'url ex  /movie/show/The%20Jungle%20Book pour The Jungle Book

        $omDBapiURL = 'http://www.omdbapi.com/?t=' . urlencode( $title ) . '&apikey=55429286';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $omDBapiURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Le blog de Samy Dindane (www.dinduks.com)');
        $jsonResponse = curl_exec ($curl);
        $response = json_decode( $jsonResponse);

        curl_close($curl);

        if(isset($response->Response) && $response->Response == "False"){
            $response = null;
        }

        return $response;
    }

}