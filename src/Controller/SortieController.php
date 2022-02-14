<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
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
    public function new(int $id,Request $request, EntityManagerInterface $entityManager,ParticipantRepository $participantRepository): Response
    {
        $sortie = new Sortie();
        $date = new \DateTime ('1:00');
        $sortie->setDuree($date);
        $sortie->setDateHeureDebut(new \DateTime('now'));
        $sortie->setDateLimiteInscription(new \DateTime('now'));
        $participant = $participantRepository->find($id);
        $sortie->setOrganisateur($participant);

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if (isset($_POST['Créée'])) {
            $sortie->setEtat('Créée');
        } else if (isset($_POST['Ouverte'])) {
            $sortie->setEtat('Ouverte');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('main_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
         ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

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
