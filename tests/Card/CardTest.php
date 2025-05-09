<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Card class.
 */
class CardTest extends TestCase
{
    /**
     * Construct the object.
     * Verify that the properties are null.
     * As default.
     */
    public function testCreateCard(): void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Card\Card", $card);

        $res = $card->getCard();

        $exp = [
            'color' => null,
            'value' => null
        ];

        $this->assertEquals($exp, $res);

        $this->assertNull($res['color']);
        $this->assertNull($res['value']);
    }

    /**
     * Test the method that returns the card as a string.
     * Also testing the setCard method to set the values.
     */
    public function testCardAsString(): void
    {
        # Arrange
        $card = new Card();

        # Act
        $card->setCard('Spades', '10');

        $res = $card->getCard();

        $exp = [
            'color' => 'Spades',
            'value' => '10'
        ];

        $resStr = $card->getCardAsString();

        # Assert
        $this->assertEquals($exp, $res);
        $this->assertEquals("[10 of Spades]", $resStr);

    }
}
