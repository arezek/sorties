<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/sorties", name="sorties_details")
     */
    public function sorties(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="sorties_cree")
     */
    public function creeSortie(): Response
    {
        return $this->render('sorties/cree.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="sorties_cancel")
     */
    public function cancel(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="sorties_edit")
     */
    public function edit(): Response
    {
        return $this->render('sorties/details.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
    /**
     * @Route("/sorties", name="sorties_history")
     */
    public function history(): Response
    {
        return $this->render('sorties/history.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }
}
