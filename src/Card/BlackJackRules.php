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
        $countAce = 0;

        foreach ($hand->getCards() as $card) {
            $cardInfo = $card->getCard();
            $cardValue = $cardInfo['value'];

            if ($cardValue === 'Ace') {
                $value += 1;
                $countAce++;
            } elseif (in_array($cardValue, ['Jack', 'Queen', 'King'])) {
                $value += 10;
            } else {
                $value += intval($cardValue);
            }
        }

        return $value;
    }

    /**
     * Decide if the ace is woth 11 or 1 points.
     * @param int $count The amount of aces to decide for.
     * @param int $value The current value of the hand.
     * @return int The updated value.
     */
    // private function decideAceValue(int $count, int $value): int
    // {

    //    for ($i = 0; $i < $count; $i++) {
    //        if ($value + 10 <= 21) {
    //            $value += 10;
    //        }
    //    }

    //    return $value;
    // }

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
    public function isBusted(CardHand $hand): bool
    {
        $handValue = $this->calculateHand($hand);

        if ($handValue > 21) {
            return true;
        }
        return false;
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
