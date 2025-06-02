<?php

namespace App\Card;

use App\Card\Card;

/**
 * Class CardHand.
 * Representing a hand of playing cards.
 */
class CardHand
{
    /**
     * @var array<int, Card> $hand Array of Card objects in the hand.
     */
    private $hand = [];

    /**
     * @var bool $standing Whether the hand is standing or not.
     */
    private $standing = false;

    /**
     * Add card to the hand.
     * @param Card $card The card to add.
     * @return void
     */
    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * Get all cards in the hand.
     * @return array<int, Card> Array of the cards in the hand.
     */
    public function getCards(): array
    {
        return $this->hand;
    }

    /**
     * Get the numbers of cards in the hand.
     * @return int The amount of cards.
     */
    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    /**
     * Set the hand to standing.
     * @return void
     */
    public function standHand(): void
    {
        $this->standing = true;
    }

    /**
     * Check if the hand is standing.
     * @return bool True if standing.
     */
    public function isHandStanding(): bool
    {
        return $this->standing;
    }

    /**
     * Clear the hand of the cards.
     * @return void
     */
    public function clearHand(): void
    {
        $this->hand = [];
    }

}
