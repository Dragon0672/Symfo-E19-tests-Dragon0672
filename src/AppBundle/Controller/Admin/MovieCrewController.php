<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\MovieCrew;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Moviecrew controller.
 *
 * @Route("/admin/moviecrew")
 */
class MovieCrewController extends Controller
{
    /**
     * Lists all movieCrew entities.
     *
     * @Route("/", name="moviecrew_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movieCrews = $em->getRepository('AppBundle:MovieCrew')->findAll();

        return $this->render('admin/moviecrew/index.html.twig', array(
            'movieCrews' => $movieCrews,
        ));
    }

    /**
     * Creates a new movieCrew entity.
     *
     * @Route("/new", name="moviecrew_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $movieCrew = new Moviecrew();
        $form = $this->createForm('AppBundle\Form\MovieCrewType', $movieCrew);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movieCrew);
            $em->flush();

            return $this->redirectToRoute('moviecrew_show', array('id' => $movieCrew->getId()));
        }

        return $this->render('admin/moviecrew/new.html.twig', array(
            'movieCrew' => $movieCrew,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a movieCrew entity.
     *
     * @Route("/{id}", name="moviecrew_show")
     * @Method("GET")
     */
    public function showAction(MovieCrew $movieCrew)
    {
        $deleteForm = $this->createDeleteForm($movieCrew);

        return $this->render('admin/moviecrew/show.html.twig', array(
            'movieCrew' => $movieCrew,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing movieCrew entity.
     *
     * @Route("/{id}/edit", name="moviecrew_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MovieCrew $movieCrew)
    {
        $deleteForm = $this->createDeleteForm($movieCrew);
        $editForm = $this->createForm('AppBundle\Form\MovieCrewType', $movieCrew);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('moviecrew_edit', array('id' => $movieCrew->getId()));
        }

        return $this->render('admin/moviecrew/edit.html.twig', array(
            'movieCrew' => $movieCrew,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a movieCrew entity.
     *
     * @Route("/{id}", name="moviecrew_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MovieCrew $movieCrew)
    {
        $form = $this->createDeleteForm($movieCrew);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movieCrew);
            $em->flush();
        }

        return $this->redirectToRoute('moviecrew_index');
    }

    /**
     * Creates a form to delete a movieCrew entity.
     *
     * @param MovieCrew $movieCrew The movieCrew entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MovieCrew $movieCrew)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('moviecrew_delete', array('id' => $movieCrew->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
