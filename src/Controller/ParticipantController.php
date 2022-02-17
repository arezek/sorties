<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/profil")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="profil_index", methods={"GET"})
     */
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Profil/{id}", name="profil_id")
     */
    public function profil(ParticipantRepository $repository, int $id): Response
    {
        //todo: afficher le nom du profil en title, si id = le miens un bouton apparait pour modifier le profil.
        //todo: modifier le chemin de navbar car redirige vers le '2'
        //todo : problème d'affichage du logo juste sur cette page...
        // $participant = $repository->findAll();
        $participant = $repository->find($id);

        return $this->render('participant/show.html.twig', [
            'participant' => $participant
        ]);
    }


    /**
     * @Route("/{id}", name="profil_show", methods={"GET"})
     */
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profil_edit", methods={"GET", "POST"})
     */
    public function edit(Participant $participant, UserInterface $user, ParticipantRepository $participantRepository, CampusRepository $campusRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        $participantMail = $participantRepository->findByMail($participant->getMail());
        $userMail = $user->getUserIdentifier();
        

        if ($participantMail != $userMail && $participant->getAdministrateur() != false) {
            
            return $this->redirectToRoute('profil_show', ['id' => $participant->getId()], Response::HTTP_SEE_OTHER);
            
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('profil_show', ['id' => $participant->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="profil_delete", methods={"POST"})
     */
    public function delete(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $participant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participant);
            $entityManager->flush();
            session_destroy();
        }

        return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
    }
}
