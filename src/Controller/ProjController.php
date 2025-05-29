<?php

namespace App\Controller;

use App\Card\GameLogic;
use App\Entity\History;
use App\Entity\User;
use App\Repository\HistoryRepository;
use App\Repository\UserRepository;


use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for the project.
 */
class ProjController extends AbstractController
{
    #[Route("/proj", name: "home_bet")]
    public function homeBet(): Response
    {
        return $this->render('proj/home.html.twig');
    }
    #[Route("/proj/loggin", name: "logg_in", methods:['GET'])]
    public function logInGet(): Response
    {
        return $this->render('proj/log_in.html.twig');
    }

    // Här kommer post sen
    //#[Route("/proj/loggin", name: "logg_in_post", methods: ['POST'])]
    //public function logInPost(Request $request, SessionInterface $session): Response
    //{}

    #[Route("/proj/create", name: "create_user", methods: ['GET'])]
    public function createGet(): Response
    {
        return $this->render('proj/create.html.twig');
    }
    #[Route("/proj/create", name: "create_user_post", methods: ['POST'])]
    public function createPost(ManagerRegistry $doctrine, 
    Request $request, SessionInterface $session): Response
    {
        $projEntityManager = $doctrine->getManager('project');

        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $bonus = $request->request->get('bonus-offer');

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($hashedPassword);
        $user->setProfilePic('profile.png');

        $projEntityManager->persist($user);

        $projEntityManager->flush($user);
    }

}
