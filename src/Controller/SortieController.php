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
    public function new(int $id, Request $request, EtatRepository $etatRepository, EntityManagerInterface $entityManager, VilleRepository $villeRepository, LieuRepository $lieuRepository, CampusRepository $campusRepository, ParticipantRepository $participantRepository): Response
    {
        $sortie = new Sortie();
        $date = new \DateTime('1:00');
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

        if ($form->isSubmitted() && $form->isValid() ) {
            $villeTemp = $villeRepository->find($_POST['optionSelectVille']);
            $campusTemp = $campusRepository->find($_POST['optionSelectCampus']);
            $tok = strtok($_POST['optionSelectLieu'], " /");
            $lieuTemp = $lieuRepository->find($tok);

            if (!is_null($villeTemp && !is_null($lieuTemp) && !is_null($campusTemp))) {

                if ($sortie->getDateHeureDebut() < $sortie->getDateLimiteInscription()){
                    $this->addFlash('notice', ' La date limite doit être avant la sortie !');
                    return $this->redirectToRoute('sortie_new', ['id'=> $id], Response::HTTP_SEE_OTHER);
                }


                $sortie->setCampus($campusTemp);
                $sortie->setLieu($lieuTemp);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('notice', ' Vous avez ajouter une sortie !');

                return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('message', ' selectionnez une valeur');
            }
        }

        return $this->renderForm('sortie/new.html.twig', [
            'creer' => 'oui',
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
    public function show(int $id, Sortie $sortie, SortieRepository $sortieRepository): Response
    {

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/publier/{id}", name="sortie_publier", methods={"GET", "POST"})
     */
    public function publier(int $id, EtatRepository $etatRepository, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
         $etatTemp = $etatRepository->findByLibelle("Ouverte");
         $sortie->setEtat($etatTemp);
         $entityManager->flush();


        return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET", "POST"})
     */
    public function edit(int $id, Request $request,EtatRepository $etatRepository, LieuRepository $lieuRepository, VilleRepository $villeRepository, Sortie $sortie, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        if (isset($_POST['Créée'])) {
            $etatTemp = $etatRepository->findByLibelle("Créée");
            $sortie->setEtat($etatTemp);
        } else if (isset($_POST['Ouverte'])) {
            $etatTemp = $etatRepository->findByLibelle("Ouverte");
            $sortie->setEtat($etatTemp);
        } else if (isset($_POST['Annulée'])) {
            $etatTemp = $etatRepository->findByLibelle("Annulée");
            $sortie->setEtat($etatTemp);
            //todo: crud de etat pour etat_edit pour justifier via un form pourquoi on annule une sortie (dedans, intégrer le etatTemp)
            //return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
        }

        $sortie->setDateHeureDebut($sortie->getDateHeureDebut());
        $sortie->setDateLimiteInscription($sortie->getDateLimiteInscription());

        if ($form->isSubmitted() && $form->isValid()) {

            if ($sortie->getDateHeureDebut() < $sortie->getDateLimiteInscription()){
                $this->addFlash('notice', ' La date limite doit être avant la sortie !');
                return $this->redirectToRoute('sortie_new', ['id'=> $id], Response::HTTP_SEE_OTHER);
            }

            $entityManager->flush();
            return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'creer' => 'non',
            'sortie' => $sortie,
            'form' => $form,
            'campuses' => $campusRepository->findAll(),
            'villes' => $villeRepository->findAll(),
            'lieux' => $lieuRepository->findAll(),
            'id' => $id,
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idS}/{idP}/{clic}", name="sortie_ajoutPraticipant", methods={"GET","POST"})
     */
    public function ajoutParticipant(string $clic, int $idS, int $idP, EtatRepository $etatRepository, EntityManagerInterface $entityManager, SortieRepository $sortieRepository, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($idP);
        $sortie = $sortieRepository->find($idS);
        $sortie->addParticipant($participant);

        //Conditions pour l'état d'une sortie :
        if ($clic == "inscrire"){
            if (count($sortie->getParticipants()) == $sortie->getNbInscriptionsMax()){
                $etatTemp = $etatRepository->findByLibelle("Fermée");
                $sortie->setEtat($etatTemp);
                $entityManager->persist($sortie);
                $entityManager->flush();
            }
        }

        $entityManager->persist($sortie);
        $entityManager->persist($participant);
        $entityManager->flush();
        
        $this->addFlash('succes', 'Ajout du Participant confirmé !');
        return $this->redirectToRoute('main_index', []);
    }
    /**
     * @Route("/supp/{idS}/{idP}/{clic}", name="sortie_suppPraticipant", methods={"GET","POST"})
     */
    public function suppParticipant(string $clic, int $idS, int $idP,EtatRepository $etatRepository, EntityManagerInterface $entityManager, SortieRepository $sortieRepository, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($idP);
        $sortie = $sortieRepository->find($idS);
        $sortie->removeParticipant($participant);

        //Conditions pour l'état d'une sortie :
        if ($clic == "desister"){
            $etatTemp = $etatRepository->findByLibelle("Ouverte");
            $sortie->setEtat($etatTemp);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }


        $entityManager->persist($sortie);
        $entityManager->persist($participant);
        $entityManager->flush();
        
        $this->addFlash('succes', 'Participant supprimé de la sortie !');
        return $this->redirectToRoute('main_index', []);
    }
}
