<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/repo", name="repo")
     */

    public function list(ParticipantRepository $part) : Response
    {

        $series = $part->findBy(['pseudo' => 'sdsdss']);
        return $this->render('main/rep.html.twig', [
            'series' => $series
        ]);
    }

}
