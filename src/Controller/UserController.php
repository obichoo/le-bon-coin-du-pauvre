<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function new(Request $request,EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod('POST')){
            $requestObj = $request->request;
            if (!empty($requestObj->get('password'))
                && !empty($requestObj->get('passwordVerify'))
                && $request->request->get('password') === $requestObj->get('passwordVerify')
                && $this->isCsrfTokenValid('register_form',$requestObj->get('csrf'))) {
                $user = new User();
                $user->setFirstName($requestObj->get('firstName'))
                    ->setLastName('lastName')
                    ->setEmail('email')
                    ->setPassword($hasher->hashPassword($user,$requestObj->get('password')));

                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        return $this->render('user/new.html.twig');
    }
}
