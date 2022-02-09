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

/**
 * @Route("/sorties", name="sorties_")
 */
class SortiesController extends AbstractController
{
    /**
     * @Route("/sorties", name="details")
     */
    public function sorties(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/CreerSortie", name="creer")
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
     * @Route("/sorties", name="cancel")
     */
    public function cancel(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="edit")
     */
    public function edit(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="history")
     */
    public function history(): Response
    {
        return $this->render('sorties/history.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
}
