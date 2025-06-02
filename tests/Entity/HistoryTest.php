<?php

namespace App\Entity\Project;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\Collection;

/**
 * Test cases for the History entity.
 */
class HistoryTest extends TestCase
{
    /**
     * Test creating a History instance.
     * Id should be Null.
     */
    public function testCreateHistory(): void
    {
        # Arrange
        $history = new History();

        # Assert
        $this->assertInstanceOf(History::class, $history);
        $this->assertNull($history->getId());
    }

    /**
     * Test the get and set methods for actiontype.
     */
    public function testSetGetActionType(): void
    {
        # Arrange
        $history = new History();

        # Act
        $history->setActionType('Uttag');
        $exp = 'Uttag';

        $res = $history->getActionType();

        # Assert
        $this->assertSame($exp, $res);
        $this->assertIsString($res);
    }

    /**
     * Test the get and set methods for amount.
     */
    public function testSetGetAmount(): void
    {
        # Arrange
        $history = new History();

        # Act
        $history->setAmount('100');
        $exp = '100';

        $res = $history->getAmount();

        # Assert
        $this->assertSame($exp, $res);

        $this->assertIsString($res);

        $this->assertEquals($exp, $res);

    }

    /**
     * Test set and get description methods.
     */
    public function testSetGetDescription(): void
    {
        # Arrange
        $history = new History();

        # Act
        $history->setDescription('Loggade in');
        $exp = 'Loggade in';

        $res = $history->getDescription();

        # Assert
        $this->assertSame($exp, $res);
        $this->assertEquals($exp, $res);
        $this->assertIsString($res);
    }

    /**
     * Test set and get created methods.
     */
    public function testSetGetCreated(): void
    {
        # Arrange
        $history = new History();
        $date = new \DateTime('2025-12-12 12:12:12');

        # Act
        $history->setCreated($date);
        $res = $history->getCreated();

        # Assert
        $this->assertSame($date, $res);
        $this->assertInstanceOf(\DateTime::class, $date);

    }

    /**
     * Test set and get userId with mock.
     */
    public function testSetGetUserIdMock(): void
    {
        # Arrange
        $history = new History();
        $user = $this->createMock(User::class);

        # Act
        $history->setUserId($user);

        $res = $history->getUserId();

        # Assert
        $this->assertSame($res, $user);
        $this->assertInstanceOf(User::class, $res);

    }

}
