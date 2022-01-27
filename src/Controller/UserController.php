<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;

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
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        UserPasswordHasherInterface $hasher,
                        UserAuthenticatorInterface $authenticator,
                        Authenticator $loginFormAuthenticator): Response
    {
        if ($request->isMethod('POST')){
            $requestObj = $request->request;
            if (!empty($requestObj->get('password'))
                && !empty($requestObj->get('passwordVerify'))
                && $request->request->get('password') === $requestObj->get('passwordVerify')
                && $this->isCsrfTokenValid('register_form',$requestObj->get('csrf'))) {
                $user = new User();
                $user->setFirstName($requestObj->get('firstName'))
                    ->setLastName($requestObj->get('lastName'))
                    ->setEmail($requestObj->get('email'))
                    ->setPassword($hasher->hashPassword($user,$requestObj->get('password')));

                $entityManager->persist($user);
                $entityManager->flush();

                return $authenticator->authenticateUser($user,$loginFormAuthenticator,$request);
            }
        }
        return $this->render('user/new.html.twig');
    }

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param $email
     * @return Response
     * @Route("/show/{email}",name="lbcdp_show")
     */
    public function show(UserRepository $userRepository,EntityManagerInterface $entityManager,$email): Response
    {
        $user = $userRepository->findOneBy(['email'=>$email]);
        $this->denyAccessUnlessGranted('USER_VIEW',$user);
        return $this->render('user/show.html.twig', ['user' => $user]);
    }
}
