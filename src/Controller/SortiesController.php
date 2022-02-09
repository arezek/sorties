<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\RegistrationFormType;
use App\Form\SortieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/sorties", name="sorties_details")
     */
    public function sorties(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/CreerSortie", name="sorties_creer")
     */
    public function creerSortie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            // encode the plain password

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie ajoutée');

        }
        return $this->render('sorties/creer.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * @Route("/sorties", name="sorties_cancel")
     */
    public function cancel(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="sorties_edit")
     */
    public function edit(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="sorties_history")
     */
    public function history(): Response
    {
        return $this->render('sorties/history.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
}
