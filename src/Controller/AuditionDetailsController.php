<?php

namespace App\Controller;

use App\Entity\AuditionDetails;
use App\Entity\Member;
use App\Form\AuditionDetailsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/auditiondetails")
 */
class AuditionDetailsController extends AbstractController
{
    /**
     * @Route("/", name="audition_details_index", methods="GET")
     */
    public function index(): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        return $this->render('audition_details/index.html.twig');
    }

    /**
     * @Route("/new", name="audition_details_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $auditionDetail = new AuditionDetails();
        $form = $this->createForm(AuditionDetailsType::class, $auditionDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($auditionDetail);
            $em->flush();

            return $this->redirectToRoute('audition_details_index');
        }

        return $this->render('audition_details/reset.html.twig', [
            'audition_detail' => $auditionDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="audition_details_show", methods="GET")
     */
    public function show(AuditionDetails $auditionDetail): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        return $this->render('audition_details/show.html.twig', ['audition_detail' => $auditionDetail]);
    }

    /**
     * @Route("/{id}/edit", name="audition_details_edit", methods="GET|POST")
     */
    public function edit(Request $request, AuditionDetails $auditionDetail): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $form = $this->createForm(AuditionDetailsType::class, $auditionDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('audition_details_edit', ['id' => $auditionDetail->getId()]);
        }

        return $this->render('audition_details/edit.html.twig', [
            'audition_detail' => $auditionDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="audition_details_delete", methods="DELETE")
     */
    public function delete(Request $request, AuditionDetails $auditionDetail): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        if ($this->isCsrfTokenValid('delete'.$auditionDetail->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($auditionDetail);
            $em->flush();
        }

        return $this->redirectToRoute('audition_details_index');
    }





}
