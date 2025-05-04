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
     * Decide the action of the dealer.
     * Based on the rules.
     * @return string The action made by dealer.
     */
    public function decideAction(): string
    {
        if ($this->rules->canDealerDraw($this->hand)) {
            return "Hit";
        }

        return "Stand";

    }
}
