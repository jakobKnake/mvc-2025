<?php

namespace App\Card;

/**
 * Trait for the status of player and dealer.
 */
trait StatusTrait
{
    /**
     * Check if the hand is busted (more than 21).
     * @return bool True or False.
     */
    public function isBusted()
    {
        return $this->rules->isBusted($this->hand);
    }

    /**
     * Check if the hand is a BlackJack.
     * @return bool True or False.
     */
    public function hasBlackJack()
    {
        return $this->rules->isBlackJack($this->hand);
    }

    /**
     * Return the score of the hand.
     * @return int The score.
     */
    public function getScore()
    {
        return $this->rules->calculateHand($this->hand);
    }

    /**
     * Set the status to 'stand' (no more cards drawn).
     * @return void
     */
    public function stand()
    {
        $this->standing = true;
    }

    /**
     * Check if the player is standing.
     * @return bool True if the player stands.
     */
    public function isStanding()
    {
        return $this->standing;
    }


}
