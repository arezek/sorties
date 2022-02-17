<?php

namespace App\Controller;


use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index(EntityManagerInterface $entityManager, EtatRepository $etatRepository, SortieRepository $sortieRepository, CampusRepository $campusRepository, Request $request): Response
    {
        //todo : quand la date d'un evenemnt est à J+1 ou meme minute +1 : mettre l'état a 'passé'.
        //todo : quand la possibilité de s'inscrire est passée, on met l'état a 'cloturé'.

        //Searchbar :
        //Nom : campusSelect / motRecherche / dateDebut / dateFin / nouveaute / organisateur / inscrit / passee
        //TODO : préremplir les dates à aujourd'hui
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $options = array(
                $campusRepository->find($_POST['campusSelect']),
                $_POST['motRecherche'],
                $_POST['dateDebut'],
                $_POST['dateFin'],
                filter_has_var(INPUT_POST,'nouveaute'),
                filter_has_var(INPUT_POST,'organisateur'),
                filter_has_var(INPUT_POST,'inscrit'),
                filter_has_var(INPUT_POST,'passee'),
            );

            for ($i = 0; $i < count($options) ; $i++) {
                if (!is_null($options[$i]) && $options[$i] == true && $options[$i] != ""){
                    dump($options[$i]);
                }
            }

            //$villeTemp = $villeRepository->find($_POST['optionSelectVille']);
            //$campusTemp = $campusRepository->find($_POST['optionSelectCampus']);
            //$tok = strtok($_POST['optionSelectLieu'], " /");
            //$lieuTemp = $lieuRepository->find($tok);
            //dump($sorties);
            //todo : trier en conséquence
            //$sorties = "";
        }

        $sortie = $sortieRepository->findAll();
        $Ajdh = new \DateTime();
        $test = $sortie[2]->getDateLimiteInscription();

        if ($Ajdh < $test){

        }

        $desister = false;
        //Conditions pour l'état d'une sortie :
        for ($i = 0; $i < count($sortie); $i ++){
            if (count($sortie[$i]->getParticipants()) == $sortie[$i]->getNbInscriptionsMax()){
                $desister = true;
            }
        }


        return $this->render('sortie/index.html.twig', [
            'desister' => $desister,
            'sorties' => $sortieRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
        ]);
    }




}







