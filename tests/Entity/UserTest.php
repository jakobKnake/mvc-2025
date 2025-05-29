<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\Collection;

/**
 * Test cases for the User entity.
 */
class UserTest extends TestCase
{
    /**
     * Test creating a User instance.
     */
    public function testCreateUser(): void
    {
        # Arrange
        $user = new User();

        # Assert
        $this->assertInstanceOf(User::class, $user);

    }

    /**
     * Test the getId method return Null for new User.
     */
    public function testGetIdNull(): void
    {
        # Arrange
        $user = new User();

        # Assert
        $this->assertNull($user->getId());
    }

    /**
     * Test the getUsername and setUsername methods.
     * Excpext correct result.
     */
    public function testSetAndGetUsername(): void
    {
        # Arrange
        $user = new User();

        # Act
        $user->setUsername("Johnny");
        $res = $user->getUsername();
        $exp = "Johnny";

        # Assert
        $this->assertSame($exp, $res);
        $this->assertEquals($exp, $res);
        $this->assertIsString($res);
        $this->assertStringStartsWith('J', $res);

    }

    /**
     * Test the setPassword and getPassword methods.
     */
    public function testSetAndGetPassword(): void
    {
        # Arrange
        $user = new User();

        # Act
        $user->setPassword('hej123');
        $res = $user->getPassword();
        $exp = 'hej123';

        # Assert
        $this->assertEquals($exp, $res);
        $this->assertSame($exp, $res);

    }

    /**
     * Test setBalance and getBalance method for user.
     * Also the setProfilePic and getProfilePic.
     */
    public function testSetAndGetBalanceAndProfilePic(): void
    {
        # Arrange
        $user = new User();

        # Act
        $user->setBalance(100);
        $res = $user->getBalance();
        $exp = '100';

        $user->setProfilePic('mario.png');
        $resPic = $user->getProfilePic();
        $expPic = 'mario.png';

        # Assert
        $this->assertEquals($exp, $res);
        $this->assertSame($exp, $res);
        $this->assertIsString($res);

        $this->assertEquals($expPic, $resPic);
        $this->assertSame($expPic, $resPic);
        $this->assertIsString($resPic);

    }

    /**
     * Test get histories for user.
     */
    public function testGetHistories(): void
    {
        # Arrange
        $user = new User();
        $histories = $user->getHistories();

        # Assert
        $this->assertInstanceOf(Collection::class, $histories);
        $this->assertCount(0, $histories);

    }

    /**
     * Test adding history with mock.
     * And remove history.
     */
    public function testAddHistoriesMock(): void
    {
        # Arrange
        $user = new User();
        $history = $this->createMock(History::class);
        

        # Act
        $user->addHistory($history);
        $res1 = $user->getHistories();

        # Assert
        $this->assertCount(1, $res1);

        # Now remove

        # Act
        $user->removeHistory($history);
        $res2 = $user->getHistories();
        

        # Assert
        $this->assertCount(0, $res2);

    }


}
