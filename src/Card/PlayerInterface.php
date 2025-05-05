<?php

namespace App\Card;

/**
 * Interface for Player class.
 */
interface PlayerInterface
{
    /**
     * Get the player hand.
     * @return array<int, Card> The player hand.
     */
    public function getHand();

    /**
     * Draw and add a card for the player.
     * @param Card $card The card to add.
     * @return void
     */
    public function addCard(Card $card);


    /**
     * Get the score of the player hand.
     * @return int The total value of the hand.
     */
    public function getScore();

}
