<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\MovieCast;
use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Moviecast controller.
 *
 * @Route("/admin/moviecast")
 */
class MovieCastController extends Controller
{
    /**
     * Lists all movieCast entities.
     *
     * @Route("/", name="moviecast_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movieCasts = $em->getRepository('AppBundle:MovieCast')->findAll();

        return $this->render('admin/moviecast/index.html.twig', array(
            'movieCasts' => $movieCasts,
        ));
    }

    /**
     * Creates a new movieCast entity.
     *
     * @Route("/new/{id}", name="moviecast_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Movie $movie)
    {
        $movieCast = new Moviecast();
        $form = $this->createForm('AppBundle\Form\MovieCastType', $movieCast);

        //le set de movie sur movieCast rend le formulaire ($form) valide
        // Malgres la contraintes movie NotBlank
        $movieCast->setMovie($movie);

        $form->handleRequest($request);   

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movieCast);
            $em->flush();

            return $this->redirectToRoute('moviecast_show',
             array(
                 'id' => $movieCast->getId(), 
                 'movie_id' => $movie->getId())
            );
        }

        return $this->render('admin/moviecast/new.html.twig', array(
            'movieCast' => $movieCast,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a movieCast entity.
     *
     * @Route("/{id}", name="moviecast_show")
     * @Method("GET")
     */
    public function showAction(MovieCast $movieCast)
    {
        $deleteForm = $this->createDeleteForm($movieCast);

        return $this->render('admin/moviecast/show.html.twig', array(
            'movieCast' => $movieCast,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing movieCast entity.
     *
     * @Route("/{id}/edit/{movie_id}", name="moviecast_edit")
     * @ParamConverter("movie", class="AppBundle:Movie", options={"id" = "movie_id"})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MovieCast $movieCast, Movie $movie)
    {
        $deleteForm = $this->createDeleteForm($movieCast);
        $editForm = $this->createForm('AppBundle\Form\MovieCastType', $movieCast);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // comme nous avons decorrelé la recuperation de movie et movie cast en l'enlevant du formulaire
            // il va falloir le setter sur l'objet movieCast : on recupere movie grace au param converter et l'url
            $movieCast->setMovie($movie);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('moviecast_edit', array('id' => $movieCast->getId(), 'movie_id' => $movie->getId()));
        }

        return $this->render('admin/moviecast/edit.html.twig', array(
            'movieCast' => $movieCast,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a movieCast entity.
     *
     * @Route("/{id}", name="moviecast_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MovieCast $movieCast)
    {
        $form = $this->createDeleteForm($movieCast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movieCast);
            $em->flush();
        }

        return $this->redirectToRoute('moviecast_index');
    }

    /**
     * Creates a form to delete a movieCast entity.
     *
     * @param MovieCast $movieCast The movieCast entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MovieCast $movieCast)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('moviecast_delete', array('id' => $movieCast->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
