<?php

namespace App\Controller;

use App\Card\GameLogic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for Black Jack game in kmom03.
 */
class BlackJackController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }
    #[Route("/game/doc", name: "game_doc")]
    public function gameDocumentation(): Response
    {
        return $this->render('game/doc.html.twig');
    }
    #[Route("/game/init", name: "init_game_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('game/init.html.twig');
    }
    #[Route("/game/init", name: "init_game_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $playerName = $request->request->get('name');
        $playerName = strval($playerName);

        $game = new GameLogic();
        $game->addPlayer($playerName);
        $game->startGame();

        $session->set("game", $game);
        $session->set("hideCard", true);

        return $this->redirectToRoute('game_play');
    }
    #[Route("/game/play", name: "game_play", methods: ['GET'])]
    public function play(SessionInterface $session): Response
    {
        /** @var GameLogic|null $game */
        $game = $session->get("game");

        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("init_game_get");
        }

        $players = $game->getPlayers();
        $player = $players[0];
        $playerBJ = $player->hasBlackJack();

        $hideCard = $session->get('hideCard');

        if ($playerBJ && $hideCard) {
            $hideCard = false;
            $session->set("hideCard", false);
        }

        $data = [
            "player" => $player,
            "dealer" => $game->getDealer(),
            "hideCard" => $hideCard,
            "gameOver" => false
        ];

        if (!$hideCard) {
            $res = $game->decideWinner();
            $outcome = reset($res);

            $data["outcome"] = $outcome;
            $data["gameOver"] = true;
        }

        return $this->render('game/play.html.twig', $data);
    }
    #[Route("/game/hit", name: "game_hit", methods: ['POST'])]
    public function playerHit(SessionInterface $session): Response
    {
        $game = $session->get("game");
        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("init_game_get");
        }

        $game->playerHit();

        $canContinue = $game->canPlayerContinue();



        if (!$canContinue) {
            $this->addFlash(
                'warning',
                'Du kan inte dra fler kort!'
            );
            $session->set("game", $game);
            return $this->redirectToRoute("game_play_dealer");

        }

        $session->set("game", $game);
        return $this->redirectToRoute("game_play");
    }
    #[Route("/game/stand", name: "game_stand", methods: ['POST'])]
    public function playerStand(SessionInterface $session): Response
    {
        $game = $session->get("game");
        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("init_game_get");
        }

        $game->playerStand();
        $this->addFlash(
            'notice',
            'Du står nu!'
        );

        $session->set("game", $game);


        return $this->redirectToRoute("game_play_dealer");
    }
    #[Route("/game/dealer", name: "game_play_dealer")]
    public function playDealer(SessionInterface $session): Response
    {
        $game = $session->get("game");
        if (!$game instanceof GameLogic) {
            return $this->redirectToRoute("init_game_get");
        }

        $session->set("hideCard", false);
        $game->playDealer();
        $session->set("game", $game);

        return $this->redirectToRoute("game_play");
    }


}
