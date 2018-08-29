<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Movie FRONT controller.
 */
class MovieController extends Controller
{
    /**
     * Lists all movie entities.
     *
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movies = $em->getRepository('AppBundle:Movie')->findAll();

        return $this->render('front/movie/index.html.twig', array(
            'movies' => $movies,
        ));
    }

    /**
     * Finds and displays a movie entity.
     *
     * @Route("/movie/show/{slug}", name="movie_show")
     * @Method("GET")
     */
    public function showAction(Movie $movie)
    {
        return $this->render('front/movie/show.html.twig', array(
            'movie' => $movie
        ));
    }
    
}
