<?php

namespace App\Controller;


use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository,CampusRepository $campusRepository): Response
    {
        //todo: envoyer une variable (true/false) pour savoir si on est connecter pour afficher son etat de connect navbar
        //todo : quand la date d'un evenemnt est à J+1 ou meme minute +1 : mettre l'état a 'passé'.
        //todo : quand la possibilité de s'inscrire est passée, on met l'état a 'cloturé'.
        // $sorties = $sortieRepository->findAll();



        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
        ]);
    }




}







