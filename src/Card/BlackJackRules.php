<?php

namespace App\Card;

/**
 * Class BlackJackRules.
 * Representing the rules of black jack following Unibets BlackJack rooms.
 */
class BlackJackRules
{
    /**
     * Calculate the value of the hand.
     * @param CardHand $hand The hand to calculate the values of.
     * @return int The value of the hand.
     */
    public function calculateHand(CardHand $hand): int
    {
        $value = 0;
        $aces = 0;

        foreach ($hand->getCards() as $card) {
            $cardInfo = $card->getCard();
            $cardValue = $cardInfo['value'];

            if ($cardValue === 'Ace') {
                $value += 1;
                $aces++;
            } elseif (in_array($cardValue, ['Jack', 'Queen', 'King'])) {
                $value += 10;
            }

            $value += intval($cardValue);
        }

        for ($i = 0; $i < $aces; $i++) {
            if ($value + 10 <= 21) {
                $value += 10;
            }
        }

        return $value;
    }

    /**
     * Determine if the first 2 cards gives BlackJack.
     * @param CardHand $hand The hand to check.
     * @return bool True or false.
     */
    public function isBlackJack(CardHand $hand): bool
    {
        $handValue = $this->calculateHand($hand);
        $cardAmount = $hand->getNumberCards();

        if ($cardAmount === 2 && $handValue === 21) {
            return true;
        }
        return false;
    }

    /**
     * Determine if the hand is busted.
     * @param CardHand $hand The hand to check.
     * @return bool True or false.
     */
    public function busted(CardHand $hand): bool
    {
        return $this->calculateHand($hand) > 21;
    }

    /**
     * Function that checks if dealer can draw.
     * Following Unibet rules the dealer will always.
     * Draw a card up to value of 16 in hand.
     * Always stays on 17.
     * @param CardHand $dealerHand The hand of the dealer.
     * @return bool True or False.
     */
    public function canDealerDraw($dealerHand): bool
    {
        $dealerValue = $this->calculateHand($dealerHand);

        if ($dealerValue < 17) {
            return true;
        }

        return false;
    }

}
