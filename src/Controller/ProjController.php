<?php

namespace App\Controller;

use App\Card\GameLogic;
use App\Entity\Project\History;
use App\Entity\Project\User;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
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
    public function homeBet(SessionInterface $session): Response
    {
        $isLoggedIn = $session->get('logged_in', false);
        $user = $session->get('user');

        $data = [
            'inloggad' => $isLoggedIn,
            'user' => $user
        ];

        return $this->render('proj/home.html.twig', $data);
    }
    #[Route("/proj/loggin", name: "logg_in", methods:['GET'])]
    public function logInGet(): Response
    {
        return $this->render('proj/log_in.html.twig');
    }

    #[Route("/proj/loggin", name: "logg_in_post", methods: ['POST'])]
    public function logInPost(
        Request $request,
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $projEntityManager = $doctrine->getManager('project');
        $userRepo = $projEntityManager->getRepository(User::class);

        $username = $request->request->get('username');
        $username = strval($username);
        $password = $request->request->get('password');
        $password = strval($password);
        $isLoggedIn = $session->get('logged_in');

        $user = $userRepo->findOneBy(['username' => $username]);

        if ($user && password_verify($password, strval($user->getPassword()))) {
            $isLoggedIn = true;

            $session->set('user', $user);
            $session->set('logged_in', $isLoggedIn);

            $this->addFlash('success', 'Du är inloggad!');
            return $this->redirectToRoute('home_bet');
        }
        $isLoggedIn = false;
        $session->set('logged_in', $isLoggedIn);
        $this->addFlash('error', 'Fel användarnamn eller lösenord!');

        return $this->redirectToRoute('logg_in');

    }

    #[Route("/proj/create", name: "create_user", methods: ['GET'])]
    public function createGet(): Response
    {
        return $this->render('proj/create.html.twig');
    }
    #[Route("/proj/create", name: "create_user_post", methods: ['POST'])]
    public function createPost(
        ManagerRegistry $doctrine,
        Request $request,
        SessionInterface $session
    ): Response {
        $projEntityManager = $doctrine->getManager('project');

        $username = $request->request->get('username');
        $username = strval($username);
        $password = $request->request->get('password');
        $password = strval($password);
        $bonus = $request->request->get('bonus_offer');


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $date = new DateTime();

        $user = new User();
        $history = new History();
        $history->setUserId($user);
        $history->setActionType('Registrering');
        $history->setDescription('Skapade nytt konto');
        $history->setAmount('0');
        $history->setCreated($date);

        $user->setUsername($username);
        $user->setPassword($hashedPassword);
        $user->setProfilePic('profile.png');
        $user->addHistory($history);

        if ($bonus) {
            $session->set('bonus', $bonus);
        }
        $user->setBalance('0');

        $projEntityManager->persist($history);
        $projEntityManager->persist($user);

        $projEntityManager->flush();

        $this->addFlash('success', 'Konto skapat!');

        return $this->redirectToRoute('logg_in');
    }
    #[Route("/proj/logout", name: "log_out")]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('success', 'Du har loggat ut.');
        return $this->redirectToRoute('home_bet');
    }

}
