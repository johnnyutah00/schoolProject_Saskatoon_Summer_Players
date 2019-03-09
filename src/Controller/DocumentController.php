<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/{id}", name="document_show", methods="GET")
     */
    public function show(Document $document): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }
        
        return $this->render('document/show.html.twig', ['document' => $document]);
    }
}
