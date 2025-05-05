<?php

namespace App\Card;

/**
 * Class representing the dealer in BlackJack.
 * Inheret from Player.
 */
class Dealer extends Player
{
    /**
     * Constructor.
     * Initializing the dealer.
     * @param BlackJackRules $rules The rules of the game.
     */
    public function __construct(BlackJackRules $rules)
    {
        parent::__construct("Dealer", $rules);
    }

    /**
     * Decide if the dealer should draw card.
     * Based on the rules.
     * @return bool True if dealer should draw.
     */
    public function shouldDraw(): bool
    {
        return $this->rules->canDealerDraw($this->hand);
    }
}
