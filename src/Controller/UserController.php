<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index(UserRepository $userRepository): Response
    {

        dd($userRepository->findAll());
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users'=> $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user")
     */
    public function new(EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setFirstName('Pablo')
            ->setLastName('Escobar')
            ->setEmail('test' . rand(1,1000) . '@gmail.com');

        $entityManager->persist($user);
        $entityManager->flush($user);

        return new Response(sprintf('Giga bien le gars #%d -> %s', $user->getId(), $user->getEmail()));
    }
}
