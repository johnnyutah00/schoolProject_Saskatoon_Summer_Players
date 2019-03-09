<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType;
use App\Entity\Member;

class LoginController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils - some commment
     * @Route("/login", name="login")
     * @return - something
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class);

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function login_check()
    {
        //If their membership has expired send them to the payment page

    }
}
