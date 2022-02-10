<?php

namespace App\Controller;


use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository,CampusRepository $campusRepository): Response
    {
        //todo: envoyer une variable (true/false) pour savoir si on est connecter pour afficher son etat de connect navbar
        //todo: modifier pour afficher la date du jour, le nom d'un participant
        //todo : quand la date d'un evenemnt est à J+1 ou meme minute +1 : mettre l'état a 'passé'.
        //todo : quand la possibilité de s'inscrire est passée, on met l'état a 'cloturé'.
       /* $sorties = $sortieRepository->findAll();


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
        ]);*/



        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
        ]);
    }




}







