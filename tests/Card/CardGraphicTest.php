<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for CardGraphic class.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct the object.
     * No need to test null default.
     * Inherits from Card.
     */
    public function testGraphicConstruct()
    {
        # Arrange
        $card = new CardGraphic();

        # Act
        $res = $card->getCard();

        # Assert
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);

        $this->assertNull($res['color']);
        $this->assertNull($res['value']);
    }
    /**
    * Test the method that returns the card as a string.
    * Should return as unicode.
    */
    public function testGraphicAsString()
    {
        # Arrange 
        $card = new CardGraphic();

        # Act
        $res = $card->getCardAsString();

        # Test that method return facedown card when null
        # Assert
        $this->assertEquals("🂠", $res);


        # Act
        $card->setCard('Spades', '10');
        $result = $card->getCardAsString();

        # Assert
        $this->assertEquals("🂪", $result);

    }
}