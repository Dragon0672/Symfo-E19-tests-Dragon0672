<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Movie;
use AppBundle\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Movie controller.
 *
 * @Route("/admin/movie")
 */
class MovieController extends Controller
{
    /**
     * Lists all movie entities.
     *
     * @Route("/", name="movie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $movies = $em->getRepository('AppBundle:Movie')->findAll();

        return $this->render('admin/movie/index.html.twig', array(
            'movies' => $movies,
        ));
    }

    /**
     * Creates a new movie entity.
     *
     * @Route("/new", name="movie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm('AppBundle\Form\MovieType', $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $file stores the uploaded picture file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $movie->getPicture();
            if ($file) {

                //je genere un nom de fichier unique avec l'extension fournie au depart pour eviter qu'il soit ecrasé à l'import
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();// moves the file to the directory where brochures are stored

                //je deplace le fichier dans le repertoire souhaité ici definit dans config.yml sous pictures_directory
                $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
                // updates the 'picture' property to store the file name
                // instead of its contents (a File type object)
                $movie->setPicture($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('admin_movie_show', array('slug' => $movie->getSlug()));
        }

        return $this->render('admin/movie/new.html.twig', array(
            'movie' => $movie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a movie entity.
     *
     * @Route("/{slug}", name="admin_movie_show")
     * @Method("GET")
     */
    public function showAction(Movie $movie)
    {
        $deleteForm = $this->createDeleteForm($movie);

        return $this->render('admin/movie/show.html.twig', array(
            'movie' => $movie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing movie entity.
     *
     * @Route("/{id}/edit", name="movie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Movie $movie)
    {
        /*
            On memorise le nom de l'ancienne image si l'on souhaite uniquement modifier une autre donnée
            ce qui nous permet de ne pas remettre notre image à null (valeur par defaut)
        */
        $oldFileName = $movie->getPicture();

        if ($oldFileName !== null) { // On transforme le nom du fichier de type String en VRAI fichier de type File afin de pouvoir récupérer toutes les propriétés et méthodes du fichier qui se trouve en BdD
            $movie->setPicture(
                new File($this->getParameter('pictures_directory') . '/' . $movie->getPicture())
            );
        }

        $deleteForm = $this->createDeleteForm($movie);
        $editForm = $this->createForm('AppBundle\Form\MovieType', $movie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // $newFile stores the uploaded picture file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $newFile */
            $newFile = $movie->getPicture();

            if ($newFile) {

                //je genere un nom de fichier unique avec l'extension fournie au depart pour eviter qu'il soit ecrasé à l'import
                $fileName = $this->generateUniqueFileName() . '.' . $newFile->guessExtension();// moves the file to the directory where brochures are stored

                //je deplace le fichier dans le repertoire souhaité ici definit dans config.yml sous pictures_directory
                $newFile->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
                // updates the 'picture' property to store the file name
                // instead of its contents (a File type object)
                // On transforme le VRAI fichier de type File en nom de fichier de type String afin de ne récupérer que le nom du fichier dans la BdD
                $movie->setPicture($fileName);
                // On supprime l'ancien fichier image si existant
                if (!empty($oldFileName)){
                    unlink($this->getParameter('pictures_directory') . '/' . $oldFileName);
                }
            } else {
                $movie->setPicture($oldFileName);
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movie_index', array('id' => $movie->getId()));
        }

        return $this->render('admin/movie/edit.html.twig', array(
            'movie' => $movie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a movie entity.
     *
     * @Route("/{id}", name="movie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Movie $movie)
    {
        $form = $this->createDeleteForm($movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($movie);
            $em->flush();
        }

        return $this->redirectToRoute('movie_index');
    }

    /**
     * Creates a form to delete a movie entity.
     *
     * @param Movie $movie The movie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Movie $movie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('movie_delete', array('id' => $movie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
