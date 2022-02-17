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
        //TODO : préremplir les dates à aujourd'hui  = fait
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
        $ajdh = new \DateTime();

        $desister = false;
        //Conditions pour l'état d'une sortie :
        for ($i = 0; $i < count($sortie); $i ++){
            $dateInscriptionSortie = $sortie[$i]->getDateLimiteInscription();
            $dateSortieDebut = $sortie[$i]->getDateHeureDebut();
            $duree = $sortie[$i]->getDuree()->format("Hi");

            $etatDeBase = $sortie[$i]->getEtat();

            if ($ajdh < $dateInscriptionSortie){
                if ($etatDeBase->getLibelle() == "Passée" || $etatDeBase->getLibelle() == "Cloturée"){
                    $etatTemp = $etatRepository->findByLibelle("Ouverte");
                } else {
                    $etatTemp = $etatRepository->findByLibelle($etatDeBase->getLibelle());
                }
            }

            if ($ajdh > $dateInscriptionSortie){
                if ($ajdh >= $dateSortieDebut){
                    if ($ajdh->format("Hi") <= $duree . $ajdh->format("Hi")){
                        $etatTemp = $etatRepository->findByLibelle("En Cours");
                    } else {
                        $etatTemp = $etatRepository->findByLibelle("Passée");
                    }
                }
                if ($ajdh < $dateSortieDebut){
                    $etatTemp = $etatRepository->findByLibelle("Cloturée");
                }
            }


            if (count($sortie[$i]->getParticipants()) == $sortie[$i]->getNbInscriptionsMax()){
                $desister = true;
            }

            $sortie[$i]->setEtat($etatTemp);
            $entityManager->persist($sortie[$i]);
            $entityManager->flush();
        }


        return $this->render('sortie/index.html.twig', [
            'date' => $ajdh,
            'desister' => $desister,
            'sorties' => $sortieRepository->findAll(),
            'campuses' => $campusRepository->findAll(),
        ]);
    }




}







