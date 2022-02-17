<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{

    /**
     * @Route("/new/{id}", name="sortie_new", methods={"GET", "POST"})
     */
    public function new(int $id,Request $request,EtatRepository $etatRepository, EntityManagerInterface $entityManager,VilleRepository $villeRepository,LieuRepository $lieuRepository, CampusRepository $campusRepository, ParticipantRepository $participantRepository): Response
    {
        $sortie = new Sortie();
        $particpant = new Participant();
        $particpant->addSorty($sortie);
        $date = new \DateTime ('1:00');
        $sortie->setDuree($date);
        $sortie->setDateHeureDebut(new \DateTime('now'));
        $sortie->setDateLimiteInscription(new \DateTime('now'));
        $participant = $participantRepository->find($id);
        $sortie->setOrganisateur($participant);

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if (isset($_POST['Créée'])) {
            $etatTemp = $etatRepository->findByLibelle("Créée");
            $sortie->setEtat($etatTemp);
        } else if (isset($_POST['Ouverte'])) {
            $etatTemp = $etatRepository->findByLibelle("Ouverte");
            $sortie->setEtat($etatTemp);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $villeTemp = $villeRepository->find($_POST['optionSelectVille']);
            $campusTemp = $campusRepository->find($_POST['optionSelectCampus']);
            $tok = strtok($_POST['optionSelectLieu'], " /");
            $lieuTemp = $lieuRepository->find($tok);

            if (!is_null($villeTemp && !is_null($lieuTemp) && !is_null($campusTemp))){
                $sortie->setCampus($campusTemp);
                $sortie->setLieu($lieuTemp);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('notice', ' Vous avez ajouter une sortie !');

                return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('message', ' selectionnez une valeur');  //todo : flash : selectionnez une valeur
            }
        }

        return $this->renderForm('sortie/new.html.twig', [
            'id' => $id,
            'sortie' => $sortie,
            'form' => $form,
            'villes' => $villeRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
            'lieux' => $lieuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(int $id,Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        //todo : accéder aux données de la table : participant_sortie sur phpmyadmin et comparer. Envoyer les pseudos vers le tableau
        $sorties = $sortieRepository->find($id);
        dump($sortie);
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
         ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        if (isset($_POST['optionSelect'])) {
            dd("hey");
        }


        if (isset($_POST['Créée'])) {
            $sortie->setEtat('Créée');
        } else if (isset($_POST['Ouverte'])) {
            $sortie->setEtat('Ouverte');
        } else if (isset($_POST['Annulée'])) {
            $sortie->setEtat('Annulée');
        }

        $sortie->setDateHeureDebut($sortie->getDateHeureDebut());
        $sortie->setDateLimiteInscription($sortie->getDateLimiteInscription());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
    }

}
