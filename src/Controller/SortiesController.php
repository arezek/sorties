<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\RegistrationFormType;
use App\Form\SortieFormType;
use App\Repository\SortieRepository;
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
     * @Route("/detail/{nom}", name="details")
     */
    public function detail(string $nom, SortieRepository $sortieRepository): Response
    {

        $sortie = $sortieRepository->find($nom);

        return $this->render('sorties/details.html.twig', [
            'sortie' => $sortie,
        ]);
    }
    /**
     * @Route("/CreerSortie", name="creer")
     */
    public function creerSortie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $sortie->setEtat('publié');
        $sortieForm = $this->createForm(SortieFormType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            // encode the plain password

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie ajoutée');

            return $this->redirectToRoute('main_index');

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
