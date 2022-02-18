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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_index")
     */
    public function index( EntityManagerInterface $entityManager, EtatRepository $etatRepository, SortieRepository $sortieRepository, CampusRepository $campusRepository, Request $request): Response
    {

        //////////////////////////// SearchBar /////////////////////////////////////////////
        $sorties = $sortieRepository->findAll();
        $sortieFlitreCampus = array();
        $sortieFlitreMot = array();
        $sortieFlitreDateDebut = array();
        $sortieFlitreDateFin = array();
        $sortieFlitreNouveaute = array();
        $sortieFlitreOrganisateur = array();
        $sortieFlitreInscrit = array();
        $sortieFlitreHistorique = array();

        $user = $this->getUser();

        $ajdh = new \DateTime();
        $dateFinRemplissage = new \DateTime();
        date_add($dateFinRemplissage, date_interval_create_from_date_string('1 month'));

        if ($_SERVER["REQUEST_METHOD"] == "POST" and $user->getUserIdentifier() != null) {
            $campus = $campusRepository->find($_POST['campusSelect']);
            $motRecherche = $_POST['motRecherche'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $nouveaute = filter_has_var(INPUT_POST,'nouveaute');
            $organisateur = filter_has_var(INPUT_POST,'organisateur');
            $inscrit = filter_has_var(INPUT_POST,'inscrit');
            $historique = filter_has_var(INPUT_POST,'passee');

            if ($campus != null){
                    for ($i = 0; $i < count($sorties) ; $i++) {
                        if ($sorties[$i]->getCampus() === $campus) {
                            $sortieFlitreCampus[] = $sorties[$i];
                        }
                    }
                } else {
                for ($i = 0; $i < count($sorties) ; $i++) {
                        $sortieFlitreCampus[] = $sorties[$i];
                    }
            }
            if ($motRecherche != null){
                    for ($i = 0; $i < count($sortieFlitreCampus) ; $i++) {
                        if (str_contains($sortieFlitreCampus[$i]->getNom(), $motRecherche )){
                            $sortieFlitreMot[] = $sortieFlitreCampus[$i];
                        }
                    }
                } else {
                    for ($i = 0; $i < count($sortieFlitreCampus) ; $i++) {
                        $sortieFlitreMot[] = $sortieFlitreCampus[$i];
                    }
                }
            if ($dateDebut != null){
                for ($i = 0; $i < count($sortieFlitreMot) ; $i++) {
                        if ($sortieFlitreMot[$i]->getDateHeureDebut()->format("Y-m-d") > $dateDebut){
                            $sortieFlitreDateDebut[] = $sortieFlitreMot[$i];
                        }
                    }
            } else {
                for ($i = 0; $i < count($sortieFlitreMot) ; $i++) {
                        $sortieFlitreDateDebut[] = $sortieFlitreMot[$i];
                    }
            }
            if ($dateFin != null){
                    for ($i = 0; $i < count($sortieFlitreDateDebut) ; $i++) {
                        if ($sortieFlitreDateDebut[$i]->getDateHeureDebut()->format("Y-m-d") < $dateFin){
                            $sortieFlitreDateFin[] = $sortieFlitreDateDebut[$i];
                        }
                    }
                } else {
                for ($i = 0; $i < count($sortieFlitreDateDebut) ; $i++) {
                        $sortieFlitreDateFin[] = $sortieFlitreDateDebut[$i];
                    }
            }
            if ($nouveaute != null){
                    for ($i = 0; $i < count($sortieFlitreDateFin) ; $i++) {
                        for ($x = 0; $x <= count($sortieFlitreDateFin[$i]->getParticipants()) ; $x++){
                            if ($sortieFlitreDateFin[$i]->getParticipants()[$x] != null){
                               $participantMail = $sortieFlitreDateFin[$i]->getParticipants()[$x]->getMail();
                                if ($participantMail != $user->getUserIdentifier()){
                                    if ($x == count($sortieFlitreDateFin[$i]->getParticipants()) - 1){
                                        $sortieFlitreNouveaute[] = $sortieFlitreDateFin[$i];
                                    }
                                }
                            }
                        }
                    }
                } else {
                for ($i = 0; $i < count($sortieFlitreDateFin) ; $i++) {
                        $sortieFlitreNouveaute[] = $sortieFlitreDateFin[$i];
                    }
            }
            if ($organisateur != null){
                for ($i = 0; $i < count($sortieFlitreNouveaute) ; $i++) {
                    $sortieOrga = $sortieFlitreNouveaute[$i]->getOrganisateur()->getMail();
                    if ($sortieOrga == $user->getUserIdentifier()){
                        $sortieFlitreOrganisateur[] = $sortieFlitreNouveaute[$i];
                    }
                }
            } else {
                for ($i = 0; $i < count($sortieFlitreNouveaute) ; $i++) {
                    $sortieFlitreOrganisateur[] = $sortieFlitreNouveaute[$i];
                }
            }
            if ($inscrit != null){
                for ($i = 0; $i < count($sortieFlitreOrganisateur) ; $i++) {
                    for ($x = 0; $x <= count($sortieFlitreOrganisateur[$i]->getParticipants()) ; $x++){
                        if ($sortieFlitreOrganisateur[$i]->getParticipants()[$x] != null){
                            $participantMail = $sortieFlitreOrganisateur[$i]->getParticipants()[$x]->getMail();
                            if ($participantMail == $user->getUserIdentifier()){
                                $sortieFlitreInscrit[] = $sortieFlitreOrganisateur[$i];
                            }
                        }else {
                        }
                    }
                }
            } else {
                for ($i = 0; $i < count($sortieFlitreOrganisateur) ; $i++) {
                    $sortieFlitreInscrit[] = $sortieFlitreOrganisateur[$i];
                }
            }
            if ($historique != null){
                for ($i = 0; $i < count($sortieFlitreInscrit) ; $i++) {
                    if ($sortieFlitreInscrit[$i]->getDateHeureDebut()->format("Y-m-d") < $ajdh->format("Y-m-d") ){
                        $sortieFlitreHistorique[] = $sortieFlitreInscrit[$i];
                    }
                }
            } else {
                for ($i = 0; $i < count($sortieFlitreInscrit) ; $i++) {
                    $sortieFlitreHistorique[] = $sortieFlitreInscrit[$i];
                }
            }
            $sorties=$sortieFlitreHistorique;
            }
        //////////////////////////// Gestion des cases du tableau 'ETAT' /////////////////////////////////////////////

        //Conditions pour l'état d'une sorties :
        for ($e = 0; $e < count($sorties); $e ++){
            $dateInscriptionSortie = $sorties[$e]->getDateLimiteInscription();
            $dateSortieDebut = $sorties[$e]->getDateHeureDebut();
            $duree = $sorties[$e]->getDuree()->format("Hi");
            $etatDeBase = $sorties[$e]->getEtat();

            if ($ajdh < $dateInscriptionSortie){
                if ($etatDeBase->getLibelle() == "Passée" || $etatDeBase->getLibelle() == "Cloturée"){
                    $etatTemp = $etatRepository->findByLibelle("Ouverte");
                } else {
                    $etatTemp = $etatRepository->findByLibelle($etatDeBase->getLibelle());
                }
            }

            if ($ajdh > $dateInscriptionSortie){
                if ($ajdh >= $dateSortieDebut ){
                    if ($ajdh->format("Hi") <= $duree . $ajdh->format("Hi" and $ajdh->format("dmy") == $dateSortieDebut->format("dmy") )){
                        $etatTemp = $etatRepository->findByLibelle("En Cours");
                    } else {
                        $etatTemp = $etatRepository->findByLibelle("Passée");
                    }
                }
                if ($ajdh < $dateSortieDebut){
                    $etatTemp = $etatRepository->findByLibelle("Cloturée");
                }
            }


            $sorties[$e]->setEtat($etatTemp);
            $entityManager->persist($sorties[$e]);
            $entityManager->flush();
        }
        /////////////////////////// Fin Gestion des cases du tableau 'ETAT' /////////////////////////////////////////////





        return $this->render('sortie/index.html.twig', [
            'creer' => 'non',
            'date' => $ajdh,
            'dateFin' => $dateFinRemplissage,
            'sorties' => $sorties,
            'campuses' => $campusRepository->findAll(),
        ]);
    }


}







