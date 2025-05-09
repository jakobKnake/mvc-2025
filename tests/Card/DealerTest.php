<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Dealer class.
 * Inherits from Player.
 */
class DealerTest extends TestCase
{
    /**
     * Test constructor
     */
    public function testDealerConstruct(): void
    {
        # Arrange
        $rules = new BlackJackRules();
        $dealer = new Dealer($rules);

        # Assert
        $this->assertInstanceOf("\App\Card\Dealer", $dealer);
    }

    /**
     * Test if the dealer should draw with his current hand.
     */
    public function testShouldDealerDraw(): void
    {
        # Arrange
        $rules = new BlackJackRules();
        $dealer = new Dealer($rules);

        $card1 = new Card();
        $card2 = new Card();

        $card1->setCard("Spades", "10");
        $card2->setCard("Diamonds", "10");

        $dealer->addCard($card1);

        # Act
        $res = $dealer->shouldDraw();

        # Assert
        $this->assertTrue($res);

        # Now add second card so should not draw.

        # Arrange
        $dealer->addCard($card2);

        # Act
        $res2 = $dealer->shouldDraw();

        # Assert
        $this->assertFalse($res2);
    }
}
