<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\OnlinePoll;
use App\Form\OnlinePollType;
use App\Repository\OnlinePollRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/board/online_poll")
 */
class OnlinePollController extends AbstractController
{
    /**
     * @Route("/", name="online_poll_index", methods={"GET","POST"})
     */
    public function index(OnlinePollRepository $onlinePollRepository): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        //Display the index page
        return $this->render('online_poll/index.html.twig', [
            'online_polls' => $onlinePollRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="online_poll_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $onlinePoll = new OnlinePoll();
        $form = $this->createForm(OnlinePollType::class, $onlinePoll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Write the new poll to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($onlinePoll);
            $entityManager->flush();

            return $this->redirectToRoute('online_poll_index');
        }

        //Render th enew page
        return $this->render('online_poll/new.html.twig', [
            'online_poll' => $onlinePoll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="online_poll_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OnlinePoll $onlinePoll): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        //Delete the poll
        if ($this->isCsrfTokenValid('delete'.$onlinePoll->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($onlinePoll);
            $entityManager->flush();
        }
        //Go back to the index
        return $this->redirectToRoute('online_poll_index');
    }
}
