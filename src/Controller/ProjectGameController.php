<?php

namespace App\Controller;

use App\Card\GameLogic;
use App\Card\CardHand;
use App\Card\CardGraphic;
use App\Entity\Project\History;
use App\Entity\Project\User;

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
    ): Response
    {
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

        $session->set("game", $game);
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
        $user = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        // This card that will face down is only used for designing the deck.
        $cardDown = new CardGraphic();

        if (!$isLoggedIn || !$user) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("init_game_get");
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
    public function makeBetsPost(Request $request,
    SessionInterface $session): Response
    {

        $SessionUser = $session->get('user');
        $isLoggedIn = $session->get('logged_in');

        if (!$isLoggedIn || !$sessionUser) {
            return $this->redirectToRoute('logg_in');
        }

        /** @var GameLogic|null $game */
        $game = $session->get("game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("init_game_get");
        }
        
        $player = $game->getPlayers()[0];
        $hands = $player->getNumbersHands();

        $bets = [];
        for ($i=0; $i < $hands; $i++) {
            $betAmount = $request->request->get("hand_$i");

            if (!$betAmount) {
                $this->addFlash('error', "Du måste lägga minst 5kr bet per hand!");
                return $this->redirectToRoute('make_bets');
            }

            $bets[$i] = intval($betAmount);
        }
        
        $session->set('bets', $bets);

        return $this->redirectToRoute("proj_game_play");

    }

    #[Route("/proj/play", name: "proj_game_play", methods: ['GET'])]
    public function projPlayGmae(SessionInterface $session): Response
    {
        
    }


}
