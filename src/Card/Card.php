<?php

namespace App\Card;

/**
 * Class representing 1 card.
 * From a standard deck of cards.
 */
class Card
{
    /**
     * @var string|null $color The color of the card.
     */
    protected $color;

    /**
     * @var string|null $value The value of the card.
     */

    protected $value;

    /**
     * Constructor, initialize the card.
     * Color and value will be set in 'draw'.
     */
    public function __construct()
    {
        $this->color = null;
        $this->value = null;
    }

    /**
     * Set color and value of card.
     * @param string $color The color of the card.
     * @param string $value The value of the card.
     */
    public function setCard(string $color, string $value): void
    {
        $this->color = $color;
        $this->value = $value;
    }

    /**
     * Get the protected value and color of the card.
     * @return array<string, string|null> An array with 'color' and 'value.
     */
    public function getCard(): array
    {
        return [
            'color' => $this->color,
            'value' => $this->value
        ];
    }

    /**
     * Get the protected value and color of the card.
     * @return string Return it as a string.
     */
    public function getCardAsString(): string
    {
        return "[{$this->value} of {$this->color}]";
    }
}
