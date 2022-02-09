<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManager;
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
    public function index(): Response
    {
        //todo: envoyer une variable (true/false) pour savoir si on est connecter pour afficher son etat de connect navbar
        //todo: modifier pour afficher la date du jour, le nom d'un participant

        $options = array('campus1', 'campus2', 'campus3');
        $valeur = array('Parc', '10/11/2023', '31/12/2024', '3/5', 'En cours', 'X', 'Jacques', 'Afficher');
        $valeurs = array($valeur, $valeur, $valeur);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'dateDuJour' => '09/02/2022',
            'participantPrenom' => 'Clement',
            'participantNom' => 'J.',
            'options' => $options,
            'valeurs' => $valeurs
        ]);
    }

    /**
     * @Route("/Profil/{id}", name="main_profil")
     */
    public function profil(): Response
    {
        //todo: afficher le nom du profil en title, si id = le miens un bouton apparait pour modifier le profil.
        //todo: modifier le chemin de navbar car redirige vers le '2'
        //todo : problème d'affichage du logo juste sur cette page...

        return $this->render('main/profil/profil.html.twig');
    }

    /**
     * @Route("/MonProfil", name="main_editprofil")
     */
    public function editProfil(): Response
    {
        return $this->render('main/profil/editProfil.html.twig');
    }

    /**
     * @Route("/Villes", name="main_villes")
     */
    public function ville(VilleRepository $villeRepository): Response
    {
      //  $ville = $villeRepository->findBy(['nom', 'codePostal', 25]);


        return $this->render('main/campus.html.twig', [

          //  "ville" => $ville

        ]);
    }
    /**
     * @Route("/Campus", name="main_campus")
     */
    public function campus(Request $request, EntityManagerInterface $entityManager, CampusRepository  $campusRepository): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {


            $entityManager->persist($campus);
            $entityManager->flush();
            $this->addFlash('success', 'Campus ajoutée');

        }
        return $this->render('main/campus.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
       // $campus = $campusRepository->findBy(['nom', 25]);


    }
}
