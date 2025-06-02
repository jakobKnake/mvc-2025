<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for CardHand class.
 */
class CardHandTest extends TestCase
{
    /**
     * Test the methods of the CardHand.
     * Add method returns void.
     * Can check results with the other methods.
     */
    public function testCardHandMethods(): void
    {
        # Arrange
        $hand = new CardHand();
        $card = new Card();
        $cardTwo = new Card();
        $card->setCard('Spades', '10');
        $cardTwo->setCard('Hearts', '10');

        # Get cards in hand using getCards. Should be empty.
        # Act
        $res = $hand->getCards();

        # Assert
        $this->assertEmpty($res);
        $this->assertCount(0, $res);

        # Can use getNumberCards to assert also. Should be 0.
        $exp = $hand->getNumberCards();

        # Assert
        $this->assertEquals(0, $exp);


        # Now add some cards
        # Arrange
        $hand->add($card);
        $result = $hand->getCards();
        $expTwo = $hand->getNumberCards();

        # Assert
        $this->assertCount(1, $result);
        $this->assertSame($card, $result[0]);
        $this->assertEquals(1, $expTwo);

        # Arrange
        $hand->add($cardTwo);
        $resultTwo = $hand->getCards();
        $expThree = $hand->getNumberCards();

        # Assert
        $this->assertCount(2, $resultTwo);
        $this->assertSame($cardTwo, $resultTwo[1]);
        $this->assertEquals(2, $expThree);

    }

    /**
     * Test the stand method to stand hand and that.
     * Is hand standing returning correct.
     */
    public function testStandHand(): void
    {
        # Arrange
        $hand = new CardHand();

        $card = new Card();
        $cardTwo = new Card();
        $card->setCard('Spades', '10');
        $cardTwo->setCard('Hearts', '10');

        $hand->add($card);
        $hand->add($cardTwo);

        # Act
        $hand->standHand();

        # Assert
        $this->assertTrue($hand->isHandStanding());
    }

    /**
     * Test clear hand method.
     * Clear the hand.
     */
    public function testClearHand(): void
    {
        # Arrange
        $hand = new CardHand();

        $card = new Card();
        $cardTwo = new Card();
        $card->setCard('Spades', '10');
        $cardTwo->setCard('Hearts', '10');

        $hand->add($card);
        $hand->add($cardTwo);

        # Act
        $res = $hand->getCards();

        $hand->clearHand();

        $res2 = $hand->getCards();

        # Assert
        $this->assertSame([], $res2);
        $this->assertCount(2, $res);

    }

}
