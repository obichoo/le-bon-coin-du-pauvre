<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="lbcdp_homepage")
     */
    public function index(AdRepository $adRepository): Response
    {

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'ads'=>$adRepository->findAll()
        ]);
    }
}
