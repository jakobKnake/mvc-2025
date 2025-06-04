<?php

namespace App\Controller;

use App\Entity\Project\History;
use App\Entity\Project\User;
use Datetime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for handling the user account.
 */
class ProfileController extends AbstractController
{
    #[Route("/proj/show_user", name: "show_user", methods: ['GET'])]
    public function showUser(SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $projEntityManager = $doctrine->getManager('project');
        $historyRepo = $projEntityManager->getRepository(History::class);

        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        $registration = $historyRepo->findOneBy([
            'user_id' => $user,
            'action_type' => 'Registrering'
        ]);


        $data = [
            'user' => $user,
            'inloggad' => $isLoggedIn,
            'registrering' => $registration
        ];

        return $this->render('proj/show_user.html.twig', $data);

    }

    #[Route("/proj/show_user", name: "update_user", methods: ['POST'])]
    public function updateUser(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $projEntityManager = $doctrine->getManager('project');

        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        $profilePic = $request->request->get('profile_pic');
        $profilePic = strval($profilePic);
        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if ($profilePic && $user) {
            $user->setProfilePic($profilePic . '.png');

            $projEntityManager->flush();
            $session->set('user', $user);

            $this->addFlash('success', 'Du har bytt Avatar');
        }

        $data = [
            'user' => $user
        ];

        return $this->redirectToRoute('show_user', $data);
    }

    #[Route("/proj/bank", name: "bank", methods: ['GET'])]
    public function bank(SessionInterface $session): Response
    {
        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        $data = [
            'user' => $user
        ];

        return $this->render('proj/bank.html.twig', $data);
    }

    #[Route("/proj/bank", name: "bank_post", methods: ['POST'])]
    public function bankPost(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $projEntityManager = $doctrine->getManager('project');

        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');
        $bonus = $session->get('bonus');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if (!$user) {
            throw $this->createNotFoundException('No user found for id');
        }

        $balanceToSet = $request->request->get('set_balance');
        $balanceToSet = strval($balanceToSet);
        $withdraw = $request->request->get('withdraw');
        $withdraw = strval($withdraw);

        $userBalance = intval($user->getBalance());

        if ($withdraw) {
            if ($userBalance <= 0 || intval($withdraw) > $userBalance) {
                $this->addFlash('error', 'Ditt uttag kan inte överskrida ditt saldo!');

                $data = ['user' => $user];
                return $this->redirectToRoute('bank', $data);
            }
            $newBalance = $userBalance - intval($withdraw);
            $newBalance = strval($newBalance);
            $user->setBalance($newBalance);

            $date = new DateTime();

            $history = new History();
            $history->setUserId($user);
            $history->setActionType('Uttag');
            $history->setDescription('Nytt uttag');
            $history->setAmount($withdraw);
            $history->setCreated($date);

            $projEntityManager->persist($history);
            $projEntityManager->flush();
            $session->set('user', $user);

            $this->addFlash('success', 'Pengarna kommer synas på ditt bankkonto om ca 3-5 arbetsdagar.');

            $data = ['user' => $user];
            return $this->redirectToRoute('bank', $data);
        }

        if ($bonus) {
            if ($balanceToSet <= '1000') {
                $bonusBalance = intval($balanceToSet) * 2;
                $bonusBalance = strval($bonusBalance);
                $user->setBalance($bonusBalance);
            } else {
                $user->setBalance($balanceToSet);
            }
            $session->set('bonus', false);
        } else {
            $newBalance = $userBalance + intval($balanceToSet);
            $newBalance = strval($newBalance);
            $user->setBalance($newBalance);
        }
        $date = new DateTime();

        $history = new History();
        $history->setUserId($user);
        $history->setActionType('Insättning');
        $history->setDescription('Ny insättning');
        $history->setAmount($balanceToSet);
        $history->setCreated($date);

        $projEntityManager->persist($history);
        $projEntityManager->flush();
        $session->set('user', $user);

        $this->addFlash('success', 'Du har gjort en ny insättning');

        $data = ['user' => $user];
        return $this->redirectToRoute('bank', $data);
    }
    #[Route("/proj/delete", name: "delete_user")]
    public function deleteUser(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response {
        $projEntityManager = $doctrine->getManager('project');

        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if (!$user) {
            throw $this->createNotFoundException('No user found for id');
        }

        $projEntityManager->remove($user);
        $projEntityManager->flush();
        $session->set('user', false);

        $this->addFlash('success', 'Radering av konto lyckades!');
        return $this->redirectToRoute('home_bet');
    }
    #[Route("/proj/history", name: "user_history")]
    public function userHistories(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response {
        $projEntityManager = $doctrine->getManager('project');
        $historyRepo = $projEntityManager->getRepository(History::class);

        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        $deposits = $historyRepo->findBy([
            'user_id' => $user,
            'action_type' => 'Insättning'
        ], ['created' => 'DESC']);

        $totalDeposit = 0;
        foreach ($deposits as $deposit) {
            $totalDeposit += intval($deposit->getAmount());
        }

        $withdrawls = $historyRepo->findBy([
            'user_id' => $user,
            'action_type' => 'Uttag'
        ], ['created' => 'DESC']);

        $totalWith = 0;
        foreach ($withdrawls as $uttag) {
            $totalWith += intval($uttag->getAmount());
        }

        $bets = $historyRepo->findBy([
                'user_id' => $user,
                'action_type' => 'Spel'
        ], ['created' => 'DESC']);

        $data = [
            'user' => $user,
            'deposits' => $deposits,
            'withdrawls' => $withdrawls,
            'total_in' => $totalDeposit,
            'total_ut' => $totalWith,
            'bets' => $bets
        ];

        return $this->render("proj/history.html.twig", $data);
    }

}
