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

        //////////////////////////// SearchBar /////////////////////////////////////////////
        $sortie = $sortieRepository->findAll();
        $sorties = $sortieRepository->findAll();
        $sortieFlitree = array();

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
            $campus = $campusRepository->find($_POST['campusSelect']);

            for ($i = 0; $i < count($options) ; $i++) {
                if ($campus != null){
                    for ($i = 0; $i < count($sorties) ; $i++) {
                        if ($sorties[$i]->getCampus() === $campus) {
                            array_push($sortieFlitree, $sorties[$i]);
                        }
                    }
                }
            }
        }





        //////////////////////////// Gestion des cases du tableau 'ETAT' /////////////////////////////////////////////
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


            $sortie[$i]->setEtat($etatTemp);
            $entityManager->persist($sortie[$i]);
            $entityManager->flush();
        }
        /////////////////////////// Fin Gestion des cases du tableau 'ETAT' /////////////////////////////////////////////

        //
        if ($sortieFlitree != null){
            $sorties=$sortieFlitree;
        }

        return $this->render('sortie/index.html.twig', [
            'creer' => 'non',
            'date' => $ajdh,
            'sorties' => $sorties,
            'campuses' => $campusRepository->findAll(),
        ]);
    }


}







