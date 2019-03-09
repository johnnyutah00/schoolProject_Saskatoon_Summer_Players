<?php

namespace App\Controller;


use App\Entity\Address;


use App\Entity\SSPShow;
use App\Entity\Member;

use App\Entity\SuggestedShow;
use App\Form\ShowType;
use App\Form\SuggestedShowType;
use App\Repository\ShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/show")
 */
class ShowController extends AbstractController
{
    /**
     * This function will merely take a show's id and then give the edit page for the appropriate show
     * @Route("/admin/edit/", name="edit_show", Methods="GET|POST")
     */
    public function edit(Request $request) : Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $id = $request->query->get('showID');

        $show = $this->getDoctrine()->getRepository(SSPShow::class)->find($id);

        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($show);
            $entityManager->flush();

            return $this->redirectToRoute('edit_index');
        }

        return $this->render('show/edit.html.twig', [
            'show' => $show,
            'form' => $form->createView(),
        ]);
    }

    /**
     * This method retrieves all show objects from the database and displays them on the show index page.
     * Note that if there are more than three shows, only three are displayed. If there are less than three then the amount retrieved are displayed.
     *
     * @Route("/", name="show_index", methods="GET|POST")
     */
    public function index(Request $request, ShowRepository $showRepository): Response
    {

        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $currentDay = new \DateTime('now');
        $showResponse = $request->query->getInt('show');

        // Get value if user has volunteered
        $hasVolunteered = $request->query->get('hasVolunteered');
        $showPasswordResetDone = $request->query->get('donePasswordResetRequest');
        $showNewPasswordResetDone = $request->query->get('donePasswordReset');

        $returnArray = array();


        switch ($showResponse)
        {
            case 0:
            case 1:
            $returnArray = $showRepository->createQueryBuilder('S')
                ->where('S.endDate > :dateToCheck')
                ->andWhere('S.status != :archived')
                ->setParameter('dateToCheck', $currentDay)
                ->setParameter('archived', "archived")
                ->orderBy('S.endDate', 'ASC')
                ->getQuery()
                ->execute();
            break;

            case -1:
                $returnArray = $showRepository->createQueryBuilder('S')
                    ->where('S.endDate < :dateToCheck')
                    ->andWhere('S.status != :archived')
                    ->setParameter('dateToCheck', $currentDay)
                    ->setParameter('archived', "archived")
                    ->orderBy('S.endDate', 'DESC')
                    ->getQuery()
                    ->execute();
            break;
        }

        // Suggested Shows Form
        $suggestedShow = new SuggestedShow();
        $form = $this->createForm(SuggestedShowType::class, $suggestedShow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $suggestedShow->setSuggestedTitle(strtoupper($suggestedShow->getSuggestedTitle()));

            $title = $this->getDoctrine()
                ->getRepository(SuggestedShow::class)
                ->findBy( ['suggestedTitle' => $suggestedShow->getSuggestedTitle()]);

            if (empty($title))
            {
                $entityManager->persist($suggestedShow);
                $entityManager->flush();
            }


            $this->addFlash('success', 'Thank you for entering a suggestion.');
            return $this->redirectToRoute('show_index');
        }

        return $this->render('show/index.html.twig',
            ['returnArray' => $returnArray,
            'showRequest' => $showResponse,
            'hasVolunteered' => $hasVolunteered,
            'showPasswordResetDone' => $showPasswordResetDone,
                'showNewPasswordResetDone' => $showNewPasswordResetDone,
            'suggested_show' => $suggestedShow,
            'form' => $form->createView(),
            ]
        ); //otherwise just return retrieved array
    }

    /**
     * This method retrieves all non-archived show objects from the database and displays them on the edit index page --Nathan
     *
     * @Route("/admin/edit_index", name="edit_index", methods="GET|POST")
     */
    public function editIndex(Request $request): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $archiveId = htmlentities($request->query->get('archiveId'));
        $doctrine = $this->getDoctrine();

        if(!empty($archiveId)){
            $entityManager = $doctrine->getManager();
            $show = $entityManager->getRepository(SSPShow::class)->find($archiveId);

            $show->setStatus("archived");
            $entityManager->flush();

            return $this->redirectToRoute('edit_index');
        }

        $showName = htmlentities(trim($request->query->get('showName'))); //grab the showname from a submit if there is one (searched for show name)

        $returnArray = array(); //array to hold all the show retrieved from the database; NOTE THAT SHOWS RETRIEVED MUST NOT HAVE THE STATUS OF "archived"

        $paramArchive = 'archived'; //create variable to set as query parameter

        $showName = empty($showName)?'%':$showName; //check if a show name was indeed submitted for search, if one wasn't it just defaults it to a wildcard

        $showName = '%' . $showName . '%'; //adds wildcards to the beginning and end of the search string submitted

        $showRepository = $doctrine->getRepository(SSPShow::class);

        $returnArray = $showRepository->createQueryBuilder('S')
            ->where('S.status != :paramArchive')
            ->andWhere('S.name LIKE :showName')
            ->setParameter('paramArchive', $paramArchive)
            ->setParameter('showName', $showName)
            ->orderBy('S.id', 'ASC')
            ->getQuery()
            ->execute();


        return $this->render('show/editIndex.html.twig', ['returnArray' => $returnArray] ); //otherwise just return retrieved array

    }

    /**
     * This function creates a new object in the database, this will be used for Board Member's only!
     *      This is used in show\index.html.twig.
     *          Make sure this section is uncommented before trying.
     *
     * @Route("/admin/new", name="show_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        //$address = new Address(1, 23, 'Block street', 'Sask', 'SK', 'S0H2T0');
        $show = new SSPShow(0, '', new \DateTime(), 0, null, '', '', '');
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();

            return $this->redirectToRoute('edit_index');
        }

        return $this->render('show/new.html.twig', [
            'show' => $show,
            'form' => $form->createView(),
        ]);
    }

    /**
     * A function that will show the "show" page after an object has been created in the database.
     *
     * @Route("/{id}", name="show_show", methods="GET")
     */
    public function show($id): Response
    {

        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        $show = $this->getDoctrine()->getRepository(SSPShow::class)->find($id);

        return $this->render('show/show.html.twig', ['show' => $show]);
    }

}
