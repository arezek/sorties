<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Participant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\ParticipantRepository;
use Doctrine\Persistence\ManagerRegistry;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');



        //°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°
        //                                  VILLES 
        //_________________________________________________________________________
        // Création de 20 villes
        $ville = [];

        for ($i = 0; $i < 20; $i++) {
            $ville[$i] = new Ville();
            $ville[$i]->setNom($faker->city());
            $ville[$i]->setCodePostal($faker->numberBetween($min = 29, $max = 79) . "000");
            $manager->persist($ville[$i]);
            $manager->flush();
        }
        //°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°
        //                                     LIEUX 
        //_________________________________________________________________________
        // Création de 30 lieux
        $lieu = [];

        for ($i = 0; $i < 30; $i++) {
            $lieu[$i] = new Lieu();
            $lieu[$i]->setNom($faker->city);
            $lieu[$i]->setRue($faker->streetName);
            $lieu[$i]->setLatitude($faker->latitude($min = -90, $max = 90));
            $lieu[$i]->setLongitude($faker->longitude($min = -180, $max = 180));
            $lieu[$i]->setVille($ville[$faker->numberBetween($min = 0, $max = count($ville) - 1)]);
            $manager->persist($lieu[$i]);
            $manager->flush();
        }
        //°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°
        //                                  ÉTAT 
        //_________________________________________________________________________
        // Création de 4 états
        $libelles = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Annuler'];

        $etat = [];

        for ($i = 0; $i < count($libelles); $i++) {
            $etat[$i] = new Etat();
            $etat[$i]->setLibelle($libelles[$i]);
            $manager->persist($etat[$i]);
            $manager->flush();
        }

        // Création de 10 campus dont le premier est par défaut
        $campuses = ['0', 'Rennes', 'Nantes', 'Quimper', 'Niort'];
        $campus = [];
        $campus[0] = new Campus();
        $campus[0]->setNom('Campus ?');

        for ($i = 1; $i < count($campuses); $i++) {
            $campus[$i] = new Campus();
            $campus[$i]->setNom($campuses[$i]);
            $manager->persist($campus[$i]);
            $manager->flush();
        }
        //°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°
        //                              PARTICIPANT
        //_________________________________________________________________________
        // Création de 50 participants
        $participant = [];

        //________________________________ Participant hasard


        for ($i = 0; $i < 50; $i++) {
            $participant[$i] = new Participant();
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setTelephone("0612345678");
            $participant[$i]->setMail($faker->email);
            $participant[$i]->setPassword($faker->password);
            $participant[$i]->setActif(true);
            $participant[$i]->setPseudo($faker->userName);
            $participant[$i]->setAdministrateur(0);
            $participant[$i]->setCampus($campus[$faker->numberBetween($min = 1, $max = count($campus) - 1)]);
            $manager->persist($participant[$i]);
            $manager->flush();
        }

        //°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°°
        //                                  SORTIES
        //_________________________________________________________________________
        // Création de 30 Sorties
        $sortie = [];

        //______________________________________________________________________ Admin

        for ($i = 0; $i < $faker->numberBetween($min = 5, $max = count($etat) - 1); $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 2, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '+ 10 days', $interval = '+20 day', $timezone = null));
            $sortie[$i]->setDuree($faker->dateTimeInInterval($startDate = 'now', $interval = '+1 day', $timezone = null));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setNbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->realText(180));

            switch ($i) {
                case '0':
                    $sortie[$i]->setEtat($etat[0]);
                    break;
                case '1':
                    $sortie[$i]->setEtat($etat[1]);
                    break;
                case '2':
                    $sortie[$i]->setEtat($etat[2]);
                    break;
                case '3':
                    $sortie[$i]->setEtat($etat[3]);
                    break;
                case '4':
                    $sortie[$i]->setEtat($etat[4]);
                    break;
            }
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setOrganisateur($participant[0]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getNbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
            $manager->flush();
        }
        //______________________________________________________________________ Hasard
        for ($i = 0; $i < 30; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '+ 10 days', $interval = '+20 day', $timezone = null));
            $sortie[$i]->setDuree($faker->dateTimeInInterval($startDate = 'now', $interval = '+1 day', $timezone = null));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setNbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setEtat($etat[0]);

            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getNbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }

            $manager->persist($sortie[$i]);
            $manager->flush();
        }
    }
}
