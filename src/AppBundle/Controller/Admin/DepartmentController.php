<?php
//Il faut que le namespace corresponde à l'endroit ou se situe le controller
namespace AppBundle\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Department;

use AppBundle\Form\DepartmentType;


/**
 * Department controller.
 *
 * Permet de prefixer toutes les routes du controller par department / ...
 * @Route("/admin/department")
 */

class DepartmentController extends Controller
{
    /**
     * @Route("/list", name="department_index")
     */
    public function indexAction(){

        $repo = $this->getDoctrine()->getRepository(Department::class);

        //je recupere tout les departements ordonné par ordre alphabetique
        $departments = $repo->findBy([], ['name' => 'ASC']);

        return $this->render('admin/department/list.html.twig',[
            'departments' => $departments
        ]);
    }

    /**
     * @Route("/show/{id}", name="department_show")
     */
    public function showAction(Department $department){

        return $this->render('admin/department/show.html.twig',[
            'department' => $department
        ]);
    }

     /**
     * @Route("/add", name="department_add")
     */
    public function addAction(Request $request){

        //creation formulaire a partir de DepartmentType
        $form = $this->createForm(DepartmentType::class);

        $form->handleRequest($request);
       
        //je teste si mon formulaire a été soumis et est valide par rapport aux contraintes fournies
        if($form->isSubmitted() && $form->isValid()){
            //grace a data-class est mappé sur la structure de departement
            $department = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($department);

            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('admin/department/add.html.twig',[
            'form' => $form->createView() //est olbigatoire pour l'affichage de la vue
        ]);
    }

    /**
     * @Route("/{id}/edit", name="department_edit")
     * 
     * Si le department n'est pas trouvé on peux lui soumettre une valeur a null par defaut
     * ce qui nous permets d'aller effectuer le test d'erreur plus bas
     */
    public function editAction(Department $department = null, Request $request){

        if(!$department) {
            throw $this->createNotFoundException(
                'Département non trouvé.'
            );
        }

        /*
          1 . Lorque que je ne passe rien , le formularaire est typée grace a la commande console
          directement sur la classe Department ce qui est equivalent a lui passe un objet de type Department a vide
          
          2. Si je souhaite remplir les données de mon formulaire, je lui passe un objet department qui contient des valeur et etant
          mappé dessus, le formulaire sais setter les champs dont il a besoin
        */
        $form = $this->createForm(DepartmentType::class, $department);
        
        //recupere et traite la requete http entrante
        $form->handleRequest($request);

         //je teste si mon formulaire a été soumis et est valide par rapport aux contraintes fournies
         if($form->isSubmitted() && $form->isValid()){
           
             $department = $form->getData();
 
             $entityManager = $this->getDoctrine()->getManager();

             $entityManager->flush();
 
             return $this->redirectToRoute('department_list');
         }

        return $this->render('admin/department/edit.html.twig',[
            'form' => $form->createView() 
        ]);
    }

     /**
     * @Route("/delete/{id}", name="department_delete")
     * 
     */
    public function deleteAction(Department $department){

        if(!$department) {
            throw $this->createNotFoundException(
                'Département non trouvé.'
            );
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($department);

        try {
            $entityManager->flush();

        } catch (\Exception $e){

            if($e->getPrevious()->getCode() === '23000'){

                echo 'Supresssion non autorisée: Ce département est affilié à 1 ou plusieurs Job';
                exit;
            } 
        }
        

        return $this->redirectToRoute('department_list');
    }
}