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
     * @var bool $standing Whether the player is standing or not.
     */
    protected $standing = false;
    

    /**
     * Constructor, initialize the player.
     */
    public function __construct(string $name, BlackJackRules $rules)
    {
        $this->name = $name;
        $this->hand = new CardHand();
        $this->rules = $rules;
    }

    /**
     * Get and return the player hand.
     * @return CardHand
     */
    public function getHand(): CardHand
    {
        return $this->hand;
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
     * Decide the action of the player.
     * Will return empty string. Indicates no action made.
     * The logic of the decisions are mad ein GameLogic class.
     * @return string The action made by the player.
     */
    public function decideAction(): string
    {
        return "";
    }

}
