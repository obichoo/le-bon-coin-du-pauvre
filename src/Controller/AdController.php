<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="lbcdp_ads")
     */
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads' => $adRepository->findAll()
        ]);
    }

    /**
     * @Route("/ad/{slug}/vote", name="lbcdp_ads_vote", methods="POST")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function vote(Ad $ad, Request $request, EntityManagerInterface $entityManager): Response
    {
        $vote = $request->request->get('vote');
        $vote === 'up' ? $ad->upVote() : $ad->downVote();
        try {
            $entityManager->flush();

            return $this->redirectToRoute('lbcdp_ad_show', [
                'slug' => $ad->getSlug()
            ]);

        } catch (OptimisticLockException $e) {
            dd($e);
        }
    }

    /**
     * @Route("/ad/{slug}", name="lbcdp_ad_show")
     */
    public function show(Ad $ad)
    {
        dd($ad);
    }
}
