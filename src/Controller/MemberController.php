<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Form\MemberUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @Route("/")
 *
 * This class will render the member registration page and write the member to the database if they are valid.
 * Author - Dylan S Cory N
 * Date - 10/25/2018
 */
class MemberController extends AbstractController
{

    /**
     * This method will handle the GET and POST requests for the registration page
     * It will create the form and renders the page. It will redirect to new page and put the member in to the database
     * after validation
     * @Route("/registration", name="member_new", methods="GET|POST")
     */
    public function newMember(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        //Build the form
        //$member = new Member();
        $member = new Member('1','Rick', 'Caron','Board Member', 1,
            'user1', '!@#$%^&',
            'Individual', 'Subscription', '', 'Saskatoon', 'S2A 4R2',
            ' SK', 'SSP', '123 456 7890', '123 Hello Street' , ' ');
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //when data is valid, encode the password hash
            $encPassword = $passwordEncoder->encodePassword($member, $member->getPassword());
            $member->setPassword($encPassword);

            //Set the role of the member because it is required for login
            $member->setRoles("ROLE_MEMBER");

            //set the postal code to database friendly data (S7J8H5)
            $enteredPostal = $member->getPostalCode();
            $member->setPostalCode(strtoupper(str_replace(" ", "",$enteredPostal)));

            $enteredPhone = $member->getPhone();
            $member->setUserName(strtolower($member->getUsername()));

            $replaceValues = array( " ", '(', ')', '-');

            $member->setPhone(str_replace($replaceValues, "",$enteredPhone));

            //Once the user successfully registers to be a member, set the last paid time
            $member->setLastDatePaid(time());

            //Save the member in the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            // Manually login member after successful registration
            $token = new UsernamePasswordToken($member, null, 'main', $member->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            //Redirect to the registration success page
            return $this->redirectToRoute("reg_success");
        }

        // Stay on the "new" page until validation is complete
        return $this->render('member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * This method will handle the GET request for the registration success page
     * It will render the page.
     *
     * @Route("/successful_registration", name="reg_success", methods="GET|POST")
     */
    public function regSuccess(Request $request) : Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        //Render the success page
        return $this->render('member/reg_success.html.twig');
    }

    /**
     * This method will display the list of board members on the about us page.
     * @param Request $request
     * @return Response
     *
     * @Route("/about", name="about_us", methods="GET|POST")
     */
    public function viewBoard(Request $request) : Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $member = $this->getDoctrine()
            ->getRepository(Member::class)
            ->findBy( ['company' => 'SSP']); //Only finds member records that have SSP listed as the company

        return $this->render('member/about.html.twig', array('memberList' => $member));
    }

    /** This function will handle the request for searching for a member.
     * Members will be added to the array if their first or last name contain characters entered
     * @Route("/admin/search_member", name="member_search", methods="GET|POST")
     * Cory Nagy
     * January 10, 2019
     */
    public function searchMember(Request $request) : Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        // Get member name to search for
        $name = trim($request->query->get('memberName'));

        // Separate the name into two parts. If first name has two parts, it'll be stored as first name: Mary Ann Thompson
        // First name: Mary Ann  Last name: Thompson
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));

        // Added "%" to the start and end to match anywhere in first or last name
        $first_name = "%" . $first_name . "%";
        // Return null for last name if empty
        $last_name = strlen($last_name) > 0 ? "%" . $last_name . "%" : null;

        // Variable to decide which div element to show on page load
        $isFound = false;

        // Open repository
        $memberRepository = $this->getDoctrine()->getRepository(Member::class);


        // Verify characters could match in first or last name
        $returnMembersArray = $memberRepository->createQueryBuilder('M')
            ->Where('M.firstName LIKE :fName')
            ->orWhere('M.lastName LIKE :f2Name')
            ->orWhere('M.firstName LIKE :lName')
            ->orWhere('M.lastName LIKE :l2Name')
            ->setParameter('fName', $first_name)
            ->setParameter('f2Name', $first_name)
            ->setParameter('lName', $last_name)
            ->setParameter('l2Name', $last_name)
            ->getQuery()
            ->execute();

        // If found, return true. Display members information on page.
        // If not found, display error message
        $isFound = count($returnMembersArray) > 0 ? true : false;

        return $this->render('member/search_member.html.twig', ['returnMembersArray' => $returnMembersArray, 'memName' => $name, 'isFound' => $isFound]);
    }

    /**
     * This function will take a member id and then give the edit page for the appropriate member
     * @Route("/member/edit/{id}", name="member_edit", Methods="GET|POST")
     */
    public function edit(Request $request, Member $member) : Response
    {
        $id = $member->getId();
        $lastSlash = strrchr($request->getUri(), "/");
        $id = substr($lastSlash, 1, strlen($id));
        $memberID = $this->getUser()->getId();
        if ($id == $memberID)
        {
            $form = $this->createForm(MemberUpdateType::class, $member);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('member_edit', ['id' => $member->getId()]);
            }

            return $this->render('member/edit.html.twig', [
                'member' => $member,
                'form' => $form->createView()
            ]);
        }

        return $this->redirectToRoute('show_index');



    }
}
