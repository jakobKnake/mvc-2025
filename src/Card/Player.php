<?php

namespace App\Card;

/**
 * Class representing a player.
 */
class Player implements PlayerInterface
{
    use StatusTrait;

    /**
     * @var  CardHand $hand The hand of cards of the player.
     */
    protected $hand;

    /**
     * @var BlackJackRules $rules The rules of the game.
     */
    protected $rules;

    /**
     * @var string $name The name of the player.
    */
    public $name;

    /**
     * @var array<int, CardHand> $hands The hands the player holds.
     */
    protected $hands;

    /**
     * @var int $currentHandIndex The index of which hand is active in hands of player.
     */
    protected $currentHandIndex;

    /**
     * Constructor, initialize the player.
     * @param string $name The name of the player.
     * @param BlackJackRules $rules The rules of the game.
     */
    public function __construct(string $name, BlackJackRules $rules)
    {
        $this->name = $name;
        $this->hand = new CardHand();
        $this->rules = $rules;
        $this->hands = [$this->hand];
        $this->currentHandIndex = 0;
    }

    /**
     * Get and return the player hand.
     * @return array<int, Card>
     */
    public function getHand(): array
    {
        return $this->hand->getCards();
    }

    /**
     * Add a card to the player.
     * @param Card $card The card to add.
     * @return void
     */
    public function addCard(Card $card): void
    {
        $this->hand->add($card);
    }

    /**
     * Get the name of the player.
     * @return string The name of the player.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get multiple hands from the player
     * @return array<int, CardHand>
     */
    public function getHands(): array
    {
        return $this->hands;
    }

    /**
     * Get the current hand of the player.
     * The active one.
     * @return CardHand The active hand.
     */
    public function getCurrentHand(): CardHand
    {
        if ($this->getCurrentHandIndex() >= $this->getNumbersHands()) {
            return $this->hands[$this->getNumbersHands() - 1];
        }
        return $this->hands[$this->currentHandIndex];
    }

    /**
     * Get the current hand index.
     * @return int The current hand index.
     */
    public function getCurrentHandIndex(): int
    {
        return $this->currentHandIndex;
    }

    /**
     * Count the hands the player got.
     * @return int The number of hands.
     */
    public function getNumbersHands(): int
    {
        return count($this->getHands());
    }

    /**
     * Check if a specific hand is busted.
     * @param int $handIndex The hand to check.
     * @return bool True or False if busted.
     */
    public function isHandBusted(int $handIndex): bool
    {
        if (!isset($this->hands[$handIndex])) {
            return false;
        }

        return $this->rules->busted($this->hands[$handIndex]);
    }
    /**
     * Get the hand score for the player.
     * Of a specific hand.
     * @param int $handIndex the hand to check.
     * @return int The score.
     */
    public function getHandScore(int $handIndex): int
    {
        if (!isset($this->hands[$handIndex])) {
            return 0;
        }

        return $this->rules->calculateHand($this->hands[$handIndex]);
    }


    /**
     * Add a new hand to the player.
     * @param CardHand $newHand A new hand.
     * @return void
     */
    public function addHand(CardHand $newHand): void
    {
        $this->hands[] = $newHand;
    }

    /**
     * Move the index one hand.
     * @return void
     */
    public function nextHand(): void
    {
        $this->currentHandIndex += 1;
    }

}
