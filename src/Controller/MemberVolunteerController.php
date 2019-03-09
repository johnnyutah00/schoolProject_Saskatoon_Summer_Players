<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberVolunteer;
use App\Form\MemberVolunteerType;
use App\Repository\MemberVolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/volunteer")
 */
class MemberVolunteerController extends AbstractController
{
    /**
     * @Route("/list", name="member_volunteer_index", methods={"GET"})
     * This function will list all volunteers and their selections
     * Cory Nagy and Taylor Beverly
     * January 29, 2019
     */
    public function index(MemberVolunteerRepository $memberVolunteerRepository): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        return $this->render('member_volunteer/index.html.twig', [
            'member_volunteers' => $memberVolunteerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="member_volunteer_new", methods={"GET","POST"})
     * This method will process a new volunteer
     * Cory Nagy and Taylor Beverly
     * January, 29 2019
     */
    public function new(Request $request): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $memberVolunteer = new MemberVolunteer();
        $errors = array();
        $isValidOption = true;

        $form = $this->createForm(MemberVolunteerType::class, $memberVolunteer);
        $form->handleRequest($request);

        // Get value of option input which isn't linked to entity
        $optionValue = $form->get('Other')->getData();

        //Check for no special characters
        if (trim(strlen($optionValue)) > 0) {
            $pattern = '/^[A-Za-z\d\s_-]+$/';
            $isValidOption = preg_match($pattern, $optionValue);

            // If there are special characters, add error message
            if (!$isValidOption)
            {
                $errors[] = "Characters like *%^$@& aren't allowed here";
            }
        }

        // Checking the length of the Option field
        $lengthForValidation = strlen($optionValue);

        // Doing validation here since input isn't tied to entity and options
        // are in an array
        if ($lengthForValidation > 25)
        {
            $errors[] = "Please let us know in less than 25 characters!";
        }
        $volunteerSelection = $memberVolunteer->getVolunteerOptions();

        // Adding option field if entered into volunteer selection
        if (!empty($optionValue))
        {
            array_push($volunteerSelection, $optionValue);
        }

        $selectionCount = count($volunteerSelection);
        if($selectionCount < 1 && $form->isSubmitted())
        {
            $errors[] = "You must select at least one option or specify your own.";
        }

        if ($form->isSubmitted() && $form->isValid() && $selectionCount > 0 && empty($errors) &&  $isValidOption == 1)
        {
            $entityManager = $this->getDoctrine()->getManager();

            //write the username and modified options array to the object
            $user = $this->getUser();
            $memberVolunteer->setVolunteerOptions($volunteerSelection);
            $memberVolunteer->setMember($user);
            $hasVolunteered = true;

            //persist object to database
            $entityManager->persist($memberVolunteer);
            $entityManager->flush();

            return $this->redirectToRoute('show_index',['hasVolunteered' => $hasVolunteered]);
        }

        return $this->render('member_volunteer/new.html.twig', [
            'member_volunteer' => $memberVolunteer,
            'form' => $form->createView(),
            'errors' => $errors,
            'isValidOption' => $isValidOption
        ]);
    }
}
