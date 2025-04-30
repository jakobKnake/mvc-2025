<?php

namespace App\Card;

/**
 * Class CardGraphic.
 * Representing a graphical playing card, inherits from Card.
 */
class CardGraphic extends Card
{
    /**
     * @var array<string, array<string|int, string>> $cardsMap An array of graphic for each card.
     */
    private $cardsMap = [
        "Spades" => [
            "Ace" => "🂡", "2" => "🂢", "3" => "🂣", "4" => "🂤", "5" => "🂥", "6" => "🂦", "7" => "🂧", "8" => "🂨",
            "9" => "🂩", "10" => "🂪", "Jack" => "🂫", "Queen" => "🂭", "King" => "🂮"
        ],
        "Hearts" => [
            "Ace" => "🂱", "2" => "🂲", "3" => "🂳", "4" => "🂴", "5" => "🂵", "6" => "🂶", "7" => "🂷", "8" => "🂸",
            "9" => "🂹", "10" => "🂺", "Jack" => "🂻", "Queen" => "🂽", "King" => "🂾"
        ],
        "Diamonds" => [
            "Ace" => "🃁", "2" => "🃂", "3" => "🃃", "4" => "🃄", "5" => "🃅", "6" => "🃆", "7" => "🃇", "8" => "🃈",
            "9" => "🃉", "10" => "🃊", "Jack" => "🃋", "Queen" => "🃍", "King" => "🃎"
        ],
        "Clubs" => [
            "Ace" => "🃑", "2" => "🃒", "3" => "🃓", "4" => "🃔", "5" => "🃕", "6" => "🃖", "7" => "🃗", "8" => "🃘",
            "9" => "🃙", "10" => "🃚", "Jack" => "🃛", "Queen" => "🃝", "King" => "🃞"
        ]
    ];
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method that overrides getCardAsString with the unicode for the cards.
     * @return string The unicode for the card.
     */
    public function getCardAsString(): string
    {
        $card = $this->getCard();
        $color = $card['color'];
        $value = $card['value'];

        if ($value === null || $color === null) {
            return $this->cardFaceDown();
        }
        return $this->cardsMap[$color][$value];
    }

    /**
     * Make the card face down
     * @return string The unicode for a facedown card.
     */
    public function cardFaceDown(): string
    {
        return "🂠";
    }

}
