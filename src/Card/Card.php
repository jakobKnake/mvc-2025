<?php

namespace App\Card;

/**
 * Class representing 1 card.
 * From a standard deck of cards.
 */
class Card
{
    /**
     * @var string $color The color of the card.
     * @var string $value The value of the card.
     */
    protected $color;
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
     * Draw a random card.
     */
    public function draw(): void
    {
        $colors = ['Spades', 'Hearts', 'Diamonds', 'Clubs'];
        $values = ['1', '2', '3', '4', '5', '6', '7', '8', '9',
                    '10', 'Jack', 'Queen', 'King'];
        
        $this->color = $colors[array_rand($colors)];
        $this->value = $values[array_rand($values)];
    
    }

    /**
     * Get the protected value and color of the card.
     * @return array An array with 'color' and 'value.
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
     * Return it as a string.
     */
    public function getCardAsString(): string
    {
        return "[{$this->value} of {$this->color}]";
    }
}
