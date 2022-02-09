<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\RegistrationFormType;
use App\Form\SortieFormType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
     * @Route("/detail/{id}", name="details")
     */
    public function detail(int $id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);
        $pseudo1 = array('Clemodd', 'clement');
        $pseudo2 = array('XEXE', 'Jacques');
        $pseudos = array($pseudo1, $pseudo2);

        return $this->render('sorties/details.html.twig', [
            'sortie' => $sortie,
            'pseudos' => $pseudos
        ]);
    }

    /**
     * @Route("/CreerSortie", name="creer")
     */
    public function creerSortie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();

        if (isset($_POST['créée'])) {
            $sortie->setEtat('créée');
        } else if (isset($_POST['ouverte'])) {
            $sortie->setEtat('ouverte');
        } else if (isset($_POST['annulée'])) {
            $sortie->setEtat('annulée');
        }



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
     * @Route("/annuler", name="annuler")
     */
    public function annuler(): Response
    {

        return $this->render('sorties/annuler.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($sortie);
        $entityManager->flush();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="edit")
     */
    public function edit(int $id, ManagerRegistry $doctrine, SortieRepository $sortieRepository, EntityManagerInterface $entityManagerI): Response
    {
        $sortieBDD = $sortieRepository->find($id);

        $entityManager = $doctrine->getManager();

        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $sortieLieu = $entityManager->getRepository(Lieu::class)->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        if($_SERVER["REQUEST_METHOD"]  === 'POST')

        {

            $adr = $_POST['adresse'];}

        if (isset($_POST['modifier'])) {

            $sortie->setNom();
            $entityManager->flush();

            return $this->redirectToRoute('main_index');
        }

        return $this->render('sorties/edit.html.twig', [
            'controller_name' => 'SortiesController',
            'sortie'=> $sortie
        ]);
    }
    /**
     * @Route("/historique", name="history")
     */
    public function history(): Response
    {
        return $this->render('sorties/history.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
}
