<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\GameLogic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Exception;

class JsonController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function getDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $session->set('json_deck', $deck);
        $data = [];
        foreach ($deck->getAllCards() as $card) {
            $data[] = $card->getCardAsString();

        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_deck", methods:['POST'])]
    public function getDeckShuffle(SessionInterface $session): Response
    {
        /** @var DeckOfCards|null $deck */
        $deck = $session->get('json_deck');
        if (!$deck) {
            $deck = new DeckOfCards();
        }
        $deck->shuffleDeck();
        $session->set('json_deck', $deck);

        $data = [];
        foreach ($deck->getAllCards() as $card) {
            $data[] = $card->getCardAsString();

        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_draw_deck", methods: ['POST'])]
    public function getDeckDraw(SessionInterface $session): Response
    {
        /** @var DeckOfCards|null $deck */
        $deck = $session->get('json_deck');
        if (!$deck) {
            $deck = new DeckOfCards();
        }
        /** @var CardGraphic $drawnCard */
        $drawnCard = $deck->drawCard();

        $session->set('json_deck', $deck);

        $data = [
            $drawnCard->getCardAsString(),
            $deck->countCards()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_draw_multi", methods: ['POST'])]
    public function drawDeckNumber(int $number, SessionInterface $session): Response
    {
        /** @var DeckOfCards|null $deck */
        $deck = $session->get('json_deck');
        if (!$deck) {
            $deck = new DeckOfCards();
        }
        $amount = $deck->countCards();
        if ($number > $amount) {
            throw new Exception("Kan inte dra mer kort är vad kortleken tillåter!");
        }

        /** @var array<int, CardGraphic> $drawnCards */
        $drawnCards = $deck->drawCard($number);
        $session->set('json_deck', $deck);

        $displayCards = [];
        foreach ($drawnCards as $card) {
            $displayCards[] = $card->getCardAsString();
        }

        $data = [
            'cards_left' => $deck->countCards(),
            'drawn_cards' => $displayCards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
    #[Route("/api/game", name: "api_game", methods: ['GET'])]
    public function bjApi(SessionInterface $session): Response
    {
        /** @var GameLogic|null $game */
        $game = $session->get("game");

        if (!$game instanceof GameLogic) {
            return new JsonResponse(['error' => 'Spelet har inte initierats'], 400);
        }

        $dealer = $game->getDealer();
        $players = $game->getPlayers();
        $player = $players[0];

        $playerHand = $player->getHand();
        $dealerHand = $dealer->getHand();

        $playerCards = [];
        foreach ($playerHand as $card) {
            $playerCards[] = $card->getCardAsString();
        }

        $dealerCards = [];
        foreach ($dealerHand as $card) {
            $dealerCards[] = $card->getCardAsString();
        }

        $data = [
            'playerCards' => $playerCards,
            'playerScore' => $player->getScore(),
            'dealerCards' => $dealerCards,
            'dealerScore' => $dealer->getScore()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;

    }

}
