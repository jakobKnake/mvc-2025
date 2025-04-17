<?php

namespace App\Controller;

use App\Card\DeckOfCards;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class JsonController
{
    #[Route("/api/lucky/number", methods: ['GET'])]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        // return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", methods: ['GET'])]
    public function getQuote(): Response
    {
        $quotes = [
            'Våga satsa för att vinnaaaaa.',
            'Nära skjuter ingen hare.',
            'Två klöver är bättre än en.',
            'Du kan alltid använda frugans uppgifter för att ta del av bonus.'
        ];
        $randomIdx = random_int(0, count($quotes) - 1);
        $displayQuote = $quotes[$randomIdx];

        $data = [
            'quote' => $displayQuote,
            'date' => date('Y-m-d'),
            'timestamp' => date('H:i:s')
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck", methods: ['GET'])]
    public function getDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards;
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
        $deck = $session->get('json_deck');
        if (!$deck) {
            $deck = new DeckOfCards;
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
        $deck = $session->get('json_deck');
        if (!$deck) {
            $deck = new DeckOfCards;
        }
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

    #[Route("/api/deck/draw/{number<\d+>}", methods: ['POST'])]
    public function drawDeckNumber(int $number, SessionInterface $session): Response
    {
        $deck = $session->get('json_deck');
        if (!$deck) {
            $deck = new DeckOfCards;
        }
        $amount = $deck->countCards();
        if ($number > $amount) {
            throw new \Exception("Kan inte dra mer kort är vad kortleken tillåter!");
        }

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

}
