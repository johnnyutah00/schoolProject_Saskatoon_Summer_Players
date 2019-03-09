<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Member;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    /**
     * Index page to Address if needed in the future. Logically we may not need this.
     *
     * @Route("/", name="address_index", methods="GET")
     */
    public function index(AddressRepository $addressRepository): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        return $this->render('address/index.html.twig', ['addresses' => $addressRepository->findAll()]);
    }

    /**
     * @Route("/new", name="address_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $address = new Address(1, 123, 'tehStreet', 'someCity','SK', 'S7M4A2');
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('address/reset.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods="GET")
     */
    public function show(Address $address): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        return $this->render('address/show.html.twig', ['address' => $address]);
    }

    /**
     * @Route("/{id}/edit", name="address_edit", methods="GET|POST")
     */
    public function edit(Request $request, Address $address): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }


        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('address_edit', ['id' => $address->getId()]);
        }

        return $this->render('address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_delete", methods="DELETE")
     */
    public function delete(Request $request, Address $address): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($address);
            $em->flush();
        }

        return $this->redirectToRoute('address_index');
    }
}
