<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Class DeckOfCards.
 * Representing a deck of cards (52).
 */
class DeckOfCards
{
    /**
     * @var array $cards An array of cards in the deck.
     */
    protected $cards;

    /**
     * Constructor.
     * Initialize the deck.
     */
    public function __construct()
    {
        $this->cards = [];
        $this->initializeDeck();
    }

    /**
     * Private method.
     * Initialize the deck with all 52 cards.
     * With correct color and value.
     */
    private function initializeDeck(): void
    {
        $colors = ['Spades', 'Hearts', 'Clubs', 'Diamonds'];
        $values = ['Ace', '2', '3', '4', '5', '6', '7', '8', '9',
                    '10', 'Jack', 'Queen', 'King'];

        foreach ($colors as $color) {
            foreach ($values as $value) {
                $card = new CardGraphic;
                $card->setCard($color, $value);
                $this->cards[] = $card;
            }
        }
    }

    /**
     * Shuffle the deck.
     */
    public function shuffleDeck(): void
    {
        shuffle($this->cards);
    }

    /**
     * Get the cards in the deck.
     * @return array Array with all the cards.
     */
    public function getAllCards(): array
    {
        return $this->cards;
    }

    /**
     * Get the amount of cards in the deck.
     * @return int The amount of cards in the deck.
     */
    public function countCards(): int
    {
        return count($this->cards);
    }

    /**
     * Draw card from deck.
     * @param int|null $num Amount of cards to draw.
     * Null = 1 card.
     * @return mixed Either return array with cards, one card or no card.
     */
    public function drawCard(?int $num = null): mixed
    {
        if ($this->countCards() <= 0) {
            return null;
        }

        if ($num === null) {
            return array_shift($this->cards);
        }

        $num = min($num, $this->countCards());

        $drawnCards = [];
        for ($i = 0; $i < $num; $i++) {
            $drawnCards[] = array_shift($this->cards);
        }

        return $drawnCards;
    }
}