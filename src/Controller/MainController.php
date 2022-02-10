<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\ParticipantType;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        //todo: envoyer une variable (true/false) pour savoir si on est connecter pour afficher son etat de connect navbar
        //todo: modifier pour afficher la date du jour, le nom d'un participant

        $sorties = $sortieRepository->findAll();


        $options = array('campus1', 'campus2', 'campus3');
        $valeur = array(1, 'Parc', '10/11/2023', '31/12/2024', '3/5', 'En cours', 'X', 'Jacques', 'Afficher');
        $valeur2 = array(2, 'Parc2', '10/11/2023', '31/12/2024', '3/5', 'En cours', 'X', 'Jacques', 'Modifier');
        $valeur3 = array(3, 'Parc3', '10/11/2023', '31/12/2024', '3/5', 'En cours', 'X', 'Jacques', 'Afficher');
        //$sorties = array($valeur, $valeur2, $valeur3);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'dateDuJour' => '09/02/2022',
            'participantPrenom' => 'Clement',
            'participantNom' => 'J.',
            'options' => $options,
            'sorties' => $sorties
        ]);
    }


    /**
     * @Route("/Villes", name="main_villes")
     */
    public function ville(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {

            //  $campus->setNom();

            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash('success', 'Ville ajoutée');
            return $this->redirectToRoute('main_villes', ['id' => $ville->getId()]);

        }
        return $this->render('main/ville.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }

    //  $ville = $villeRepository->findBy(['nom', 'codePostal', 25]);
    public function suppVille(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        {
            $entityManager->remove($ville);
            $entityManager->flush();
        }
        return $this->redirectToRoute('main_villes', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/Campus", name="main_campus")
     */
    public function campus(Request $request, EntityManagerInterface $entityManager, CampusRepository $campusRepository): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {

            // $campus->setNom();

            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash('success', 'Campus ajoutée');
            return $this->redirectToRoute('main_campus', ['id' => $campus->getId()]);

        }


        return $this->render('main/campus.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
        // $campus = $campusRepository->findBy(['nom', 25]);
    }

    public function suppCampus(Request $request, Campus $campus, EntityManagerInterface $entityManager): Response
    {
        {
            $entityManager->remove($campus);
            $entityManager->flush();
        }
        return $this->redirectToRoute('main_campus');
    }


    /**
     * @Route("/AffCampus", name="main_affCampus")
     */
    public function affCampus(CampusRepository $campusRepository): Response
    {

        $campus = $campusRepository->findAll();



        return $this->render('main/campusAfficher.html.twig', [
            'campus' => $campus
        ]);
    }

    /**
     * @Route("/ModifCampus", name="main_modifCampus")
     */
    public function modifCampus(CampusRepository $campusRepository, int $id): Response
    {

        $campus = $campusRepository->find($id);



        return $this->render('main/campusModifier.html.twig', [
            'campus' => $campus
        ]);
    }
}





