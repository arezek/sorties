<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieu")
 */
class LieuController extends AbstractController
{
    /**
     * @Route("/", name="lieu_index", methods={"GET"})
     */
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="lieu_new", methods={"GET", "POST"})
     */
    public function new(int $id, Request $request,VilleRepository $villeRepository, EntityManagerInterface $entityManager): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $villeTemp = $villeRepository->find($_POST['optionSelectVille']);
            if (!is_null($villeTemp)){
                $lieu->setVille($villeTemp);
                $entityManager->persist($lieu);
                $entityManager->flush();

                return $this->redirectToRoute('sortie_new', ['id'=>$id], Response::HTTP_SEE_OTHER);
            } else {
                //todo: rajouter une erreur : merci de sélectionner une valeur pour la ville
            }
        }

        return $this->renderForm('lieu/new.html.twig', [
            'id' => $id,
            'lieu' => $lieu,
            'form' => $form,
            'villes' => $villeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="lieu_show", methods={"GET"})
     */
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="lieu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lieu_delete", methods={"POST"})
     */
    public function delete(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lieu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lieu_index', [], Response::HTTP_SEE_OTHER);
    }
}
