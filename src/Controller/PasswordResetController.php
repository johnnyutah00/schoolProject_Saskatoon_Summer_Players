<?php

namespace App\Controller;

use App\Entity\PasswordReset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Member;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
/**
 * @Route("/password")
 */
class PasswordResetController extends AbstractController
{
//    /**
//     * @Route("/", name="password_reset_index", methods={"GET"})
//     */
//    public function index(PasswordResetRepository $passwordResetRepository): Response
//    {
//        return $this->render('password_reset/index.html.twig', [
//            'password_resets' => $passwordResetRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="password_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $recoverValue = trim($request->query->get('recovervalue'));
        $em = $this->getDoctrine()->getManager();
        $passwordResetRequest = $em->getRepository(PasswordReset::class)->findOneBy(['recoveryValue' => $recoverValue]);

        //$defaultData = ['message' => 'Data to send if needed'];
        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The passwords must match',
                'required' => true,
                'first_options' => ['label' => 'Enter new password'],
                'second_options' => ['label' => 'Confirm password'],
                'constraints' => new Regex(['pattern' => '/^(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{6,}$/',
                    'message' => "Password must contain: An uppercase letter, a lowercase letter, and at least 6 characters"])
            ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $member = $passwordResetRequest->getMember();
            $encPassword = $passwordEncoder->encodePassword($member, $form->get('password')->getData());
            $member->setPassword($encPassword);
            $em->persist($member);
            $em->flush();

            $blankMem = new Member();
            $passwordResetRequest->setMember($blankMem);
            $em->remove($passwordResetRequest);
            $em->flush();

            return $this->redirectToRoute('show_index',['donePasswordReset' => true]);
        }

        return $this->render('password_reset/new.html.twig', ['form' => $form->createView(), 'isValidRequest' => $passwordResetRequest]);
    }


    /**
     * @Route("/reset", name="password_reset", methods={"GET","POST"})
     * @throws
     */
    public function new(Request $request, \Swift_Mailer $mailer, ValidatorInterface $validator): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        // Create simple form for email entry
        $defaultData = ['message' => 'Data to send if needed'];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys

            $emailAddress = strtolower($form->get('email')->getData());
            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "Invalid Email Address";
            $emailConstraint->mode = "strict";

            // Use validator to validate
            $isValidEmail = false;
            $errors = $validator->validate($emailAddress, $emailConstraint);
            if (count($errors) == 0)
            {
                // Valid email
                $isValidEmail = true;

                $member = $this->getMember($emailAddress);

                if($member == null)
                {
                    return $this->render('password_reset/reset.html.twig', [
                        'form' => $form->createView(),
                        'memberEmailNotFound' => "The email provided does not match any known members.",

                    ]);
                }

                $resetExists = $this->checkResetExists($member);

            } else{
                // Invalid email
                $isValidEmail = false;
            }
            if ($isValidEmail && !empty($member) && $resetExists == false)
            {
                //The function returns a random string, suitable for cryptographic use,
                // of the number bytes passed as an argument.
                $random = random_bytes(10);
                $recoverValue = md5($random);
                $passwordReset = new PasswordReset();
                $passwordReset->setMember($member);
                //The random_bytes() function returns a binary string which may contain the \0 character.
                //The solution is to hash the value returned by random_bytes() with a hashing function such as md5
                $passwordReset->setRecoveryValue($recoverValue);
                $passwordReset->setTimeGenerated(new DateTime('now'));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($passwordReset);
                $entityManager->flush();

                $url = $this->generateUrl('password_new');

                $message = (new \Swift_Message("Password Reset"))
                    ->setFrom('Saskatoonsummerplayers@gmail.com')
                    ->setTo($emailAddress)
                    ->setBody(
                        '<html>' .
                        ' <body>' .
                        '  Here is you link http://localhost:8000/password/new' .
                        $url. "?recovervalue=".$recoverValue.
                        '  Rest of message' .
                        ' </body>' .
                        '</html>',
                        'text/html' // Mark the content-type as HTML
                    );


                $mailer->send($message, $failures);

                return $this->redirectToRoute('show_index',['donePasswordResetRequest' => true]);
            }
            else {

                // Not a valid email, return to page with errors
                return $this->render('password_reset/reset.html.twig', [
                    'form' => $form->createView(),
                    'errors' => $errors,
                    'resetExists' => $resetExists,
                    'requestAlreadyExists' => $resetExists

                ]);
            }

        }
        $formErrors = $form->getErrors();

        return $this->render('password_reset/reset.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * This method will fetch a member based on email address
     * @param string $emailAddress
     * @return object|Member
     *
     */
    public function getMember(string $emailAddress)
    {
        // Open repository and find user
        $member = $this->getDoctrine()->getRepository(Member::class)
                ->findOneBy(['userName' => $emailAddress]);

        return $member;
    }

    public function checkResetExists(Member $member)
    {
            // Open repository and find Password reset if exists
        $id = $member->getId();
        $passwordReset = $this->getDoctrine()->getRepository(PasswordReset::class)
                ->findOneBy(['member' => $id]);

            if (!empty($passwordReset))
            {
                return true;
            }
            else {
                return false;
            }
    }

//    /**
//     * @Route("/{id}", name="password_reset_show", methods={"GET"})
//     */
//    public function show(PasswordReset $passwordReset.html.twig): Response
//    {
//        return $this->render('password_reset/show.html.twig', [
//            'password_reset' => $passwordReset.html.twig,
//        ]);
//    }
//
//    /**
//     * @Route("/{id}/edit", name="password_reset_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, PasswordReset $passwordReset.html.twig): Response
//    {
//        $form = $this->createForm(PasswordResetType::class, $passwordReset.html.twig);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('password_reset_index', [
//                'id' => $passwordReset.html.twig->getId(),
//            ]);
//        }
//
//        return $this->render('password_reset/edit.html.twig', [
//            'password_reset' => $passwordReset.html.twig,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="password_reset_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, PasswordReset $passwordReset.html.twig): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$passwordReset.html.twig->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($passwordReset.html.twig);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('password_reset_index');
//    }
}
