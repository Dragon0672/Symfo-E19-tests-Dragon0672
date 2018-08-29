<?php
namespace AppBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//permet de gerer toute la partie securité (login form ...) de l'application
class SecurityController extends Controller
{
     /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        //recupere les erreur poentielle de login
        $error = $authenticationUtils->getLastAuthenticationError();

        //recupere le dernier user en cas d'erreur afin de pouvoir le reafficher
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}