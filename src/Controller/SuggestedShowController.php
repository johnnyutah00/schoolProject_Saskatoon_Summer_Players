<?php

namespace App\Controller;

use App\Entity\SuggestedShow;
use App\Form\SuggestedShowType;
use App\Repository\SuggestedShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;

/**
 * @Route("/admin/suggested/show")
 */
class SuggestedShowController extends AbstractController
{
    /**
     * Suggested shows page.  Only available to the General Manager.
     * @Route("/", name="suggested_show_index", methods={"GET"})
     * @author Kate Zawada and MacKenzie Wilson
     */
    public function index(SuggestedShowRepository $suggestedShowRepository): Response
    {
        //Call the voters to check if membership time is up
        if (!$this->isGranted("activeMember", new Member()))
        {
            //Membership is over a year old so log the user out and redirects to the homepage.
            return $this->redirectToRoute("logout");
        }

        return $this->render('suggested_show/index.html.twig', [
            'suggested_shows' => $suggestedShowRepository->findAll(),
        ]);
    }


    /**
     * Delete a Suggested Show from the Suggested Shows page (only available to GMs)
     * @author Kate Zawada and MacKenzie Wilson
     * @Route("/{id}", name="suggested_show_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SuggestedShow $suggestedShow): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suggestedShow->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($suggestedShow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('suggested_show_index');
    }
}
