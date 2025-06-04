<?php

namespace App\Controller;

use App\Card\GameLogic;
use App\Card\CardHand;
use App\Card\CardGraphic;
use App\Entity\Project\History;
use App\Entity\Project\User;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for the project.
 */
class ProjectGameController extends AbstractController
{
    #[Route("/proj/init_game", name: "proj_init_game", methods: ['GET'])]
    public function initGame(SessionInterface $session): Response
    {
        /** @var User|null $user */
        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        $data = [
            'inloggad' => $isLoggedIn,
            'user' => $user
        ];

        return $this->render('game/init.html.twig', $data);
    }

    #[Route("/proj/init", name: "proj_init_post", methods: ['POST'])]
    public function initGameCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        /** @var User|null $user */
        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');
        $game = new GameLogic();

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        $playerName = $request->request->get('name');
        $playerName = strval($playerName);
        $game->addPlayer($playerName);

        $hands = $request->request->get('hands');
        $hands = intval($hands);

        $player = $game->getPlayers()[0];
        for ($i = 1; $i < $hands; $i++) {
            $newHand = new CardHand();
            $player->addHand($newHand);
        }

        $game->startProjGame();

        $session->set("proj_game", $game);
        $session->set("hideCard", true);

        $data = [
            'inloggad' => $isLoggedIn,
            'user' => $user,
        ];

        return $this->redirectToRoute('make_bets', $data);
    }

    #[Route("/proj/bets", name: "make_bets", methods: ['GET'])]
    public function makeBets(SessionInterface $session): Response
    {
        /** @var User|null $user */
        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        // This card that will face down is only used for designing the deck.
        $cardDown = new CardGraphic();

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }

        $player = $game->getPlayers()[0];

        $data = [
            'inloggad' => $isLoggedIn,
            'user' => $user,
            'cardDown' => $cardDown,
            'player' => $player
        ];

        return $this->render("proj/bets.html.twig", $data);
    }
    #[Route("/proj/bets", name: "make_bets_post", methods: ['POST'])]
    public function makeBetsPost(
        Request $request,
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

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }

        $player = $game->getPlayers()[0];
        $hands = $player->getNumbersHands();

        $bets = [];
        $totalBettingAmount = 0;
        for ($i = 0; $i < $hands; $i++) {
            $handBets = $request->request->all("hand_$i");

            if (empty($handBets)) {
                $this->addFlash('error', "Du måste lägga minst 5kr bet per hand!");
                return $this->redirectToRoute('make_bets');
            }
            $totalHandBet = array_sum(array_map('intval', $handBets));

            if ($totalHandBet < 5) {
                $this->addFlash('error', "Du måste lägga minst 5kr bet per hand!");
                return $this->redirectToRoute('make_bets');
            }

            $bets[$i] = $handBets;
            $totalBettingAmount += $totalHandBet;
        }

        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if (!$user) {
            throw $this->createNotFoundException('No user found for id');
        }

        $userBalance = intval($user->getBalance());

        if ($userBalance < $totalBettingAmount) {
            $this->addFlash('error', "Otillräckligt saldo!");
            return $this->redirectToRoute('make_bets');
        }

        $newBalance = $userBalance - $totalBettingAmount;
        $newBalance = strval($newBalance);
        $user->setBalance($newBalance);
        $session->set('bets', $bets);

        $projEntityManager->flush();
        $session->set('user', $user);

        return $this->redirectToRoute("proj_game_play");

    }

    #[Route("/proj/play", name: "proj_game_play", methods: ['GET'])]
    public function projPlayGame(
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

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }


        $player = $game->getPlayers()[0];
        $currentHandIndex = $player->getCurrentHandIndex();

        $hideCard = $session->get('hideCard');

        $data = [
            "player" => $player,
            "dealer" => $game->getDealer(),
            "hideCard" => $hideCard,
            "gameOver" => false,
            'inloggad' => $isLoggedIn,
            'user' => $sessionUser,
            'currentHandIndex' => $currentHandIndex
        ];

        if (!$hideCard) {
            $res = $game->decideWinner();
            $outcome = reset($res);
            if ($outcome === false) {
                $outcome = [];
            }

            /** @var array<int, array<string|int>> $bets */
            $bets = $session->get('bets');
            $totalBet = 0;
            $totalWin = 0;
            foreach ($bets as $handIndex => $handBets) {
                $handBet = array_sum(array_map('intval', $handBets));
                $totalBet += $handBet;

                $handOutcome = $outcome[$handIndex];

                if ($handOutcome === 'Win') {
                    $totalWin += $handBet * 2;
                } elseif ($handOutcome == "BlackJack") {
                    $totalWin += $handBet * 2.5;
                } elseif ($handOutcome == "Push") {
                    $totalWin += $handBet;
                }
            }

            $netRes = $totalWin - $totalBet;

            $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
            if (!$user) {
                throw $this->createNotFoundException('No user found for id');
            }

            $userBalance = intval($user->getBalance());
            $newBalance = $userBalance + $totalWin;
            $user->setBalance(strval($newBalance));
            $session->set('user', $user);

            $date = new DateTime();

            $history = new History();
            $history->setUserId($user);
            $history->setActionType('Spel');
            $history->setDescription('Resultat från BlackJack');
            $history->setAmount(strval($netRes));
            $history->setCreated($date);

            $projEntityManager->persist($history);
            $projEntityManager->flush();

            if (!empty($outcome)) {
                $data["outcome"] = implode(", ", $outcome);
                $data["gameOver"] = true;
            }

        }

        return $this->render('proj/play.html.twig', $data);
    }

    #[Route("/proj/hit", name: "proj_game_hit", methods: ['POST'])]
    public function projGameHit(SessionInterface $session): Response
    {
        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }

        $game->playerHit();

        $canContinue = $game->canPlayerContinue();

        if (!$canContinue) {
            $this->addFlash(
                'warning',
                'Du kan inte dra fler kort!'
            );
            $game->playDealer();
            $session->set("hideCard", false);

            $this->addFlash(
                'notice',
                'Dealerns tur!'
            );
        }

        $session->set("proj_game", $game);

        return $this->redirectToRoute("proj_game_play");
    }

    #[Route("/proj/stand", name: "proj_game_stand", methods: ['POST'])]
    public function projGameStand(SessionInterface $session): Response
    {
        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }

        $isPlayerStanding = $game->playerStand();
        $this->addFlash(
            'notice',
            'Du står nu!'
        );

        if ($isPlayerStanding) {
            $game->playDealer();
            $session->set("hideCard", false);

            $this->addFlash(
                'notice',
                'Dealerns tur!'
            );
        }

        $session->set("proj_game", $game);

        return $this->redirectToRoute("proj_game_play");
    }

    #[Route("/proj/split", name: "proj_game_split", methods: ['POST'])]
    public function projGameSplit(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response {
        $projEntityManager = $doctrine->getManager('project');

        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        /** @var array<int, array<string|int>>|null $bets */
        $bets = $session->get('bets');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }

        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if (!$user) {
            throw $this->createNotFoundException('No user found for id');
        }

        $player = $game->getPlayers()[0];
        $currentHandIndex = $player->getCurrentHandIndex();

        if ($bets && $bets[$currentHandIndex]) {
            $currBet = array_sum(array_map('intval', $bets[$currentHandIndex]));
            $userBalance = intval($user->getBalance());

            if ($userBalance < $currBet) {
                $this->addFlash('error', "Otillräckligt saldo för att splitta!");
                $session->set("proj_game", $game);
                return $this->redirectToRoute("proj_game_play");
            }

            $splitWorked = $game->playerSplit();

            if ($splitWorked) {
                $newBalance = $userBalance - $currBet;
                $user->setBalance(strval($newBalance));

                $newHandIndex = $player->getNumbersHands() - 1;
                $bets[$newHandIndex] = [$currBet];

                $session->set('bets', $bets);
                $projEntityManager->flush();
                $session->set('user', $user);

                $this->addFlash('success', 'Dina kort har Splittats!');
            } else {
                $this->addFlash('error', 'Kan inte splitta dessa kort!');
            }
        }

        $session->set("proj_game", $game);

        return $this->redirectToRoute("proj_game_play");
    }

    #[Route("/proj/double", name: "proj_game_double", methods: ['POST'])]
    public function projGameDouble(
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $projEntityManager = $doctrine->getManager('project');

        /** @var User|null $sessionUser */
        $sessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        /** @var array<int, array<string|int>>|null $bets */
        $bets = $session->get('bets');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("proj_game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("proj_init_game");
        }

        $player = $game->getPlayers()[0];
        $currentHandIndex = $player->getCurrentHandIndex();
        $currentHand = $player->getCurrentHand();

        if ($currentHand->getNumberCards() !== 2) {
            $this->addFlash('error', 'Du kan bara dubbla i första ronden!');
            $session->set("proj_game", $game);
            return $this->redirectToRoute("proj_game_play");
        }

        $user = $projEntityManager->getRepository(User::class)->find($sessionUser->getId());
        if (!$user) {
            throw $this->createNotFoundException('No user found for id');
        }

        if ($bets && $bets[$currentHandIndex]) {
            $currBet = array_sum(array_map('intval', $bets[$currentHandIndex]));
            $userBalance = intval($user->getBalance());

            if ($userBalance < $currBet) {
                $this->addFlash('error', "Otillräckligt saldo för att dubbla!");
                $session->set("proj_game", $game);
                return $this->redirectToRoute("proj_game_play");
            }


            $bets[$currentHandIndex] = [$currBet * 2];
            $newBalance = $userBalance - $currBet;
            $newBalance = strval($newBalance);
            $user->setBalance($newBalance);

            $session->set('bets', $bets);
            $projEntityManager->flush();
            $session->set('user', $user);
        }

        $game->playerHit();

        $isPlayerStanding = $game->playerStand();

        $this->addFlash('notice', 'Insatsen dubblad och kort är draget!');

        if ($isPlayerStanding) {
            $game->playDealer();
            $session->set("hideCard", false);
            $this->addFlash('notice', 'Dealerns tur!');
        }

        $session->set("proj_game", $game);

        return $this->redirectToRoute("proj_game_play");
    }


}
