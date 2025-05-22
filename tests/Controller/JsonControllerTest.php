<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for JsonController.
 */
class JsonControllerTest extends WebTestCase
{
    /**
     * Test that api/deck route works
     */
    public function testJsonControllerRoute(): void
    {
        # Arrange
        $client = static::createClient();

        # Act
        $client->request('GET', '/api/deck');

        # Assert
        $this->assertResponseIsSuccessful();

    }

    /**
     * Test the POST methods for shuffle and draw card.
     */
    public function testPostRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/api/deck/shuffle', '/api/deck/draw', '/api/deck/draw/4'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('POST', $route);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * Try drawing more cards than ok, throws error.
     */
    public function testDrawTooMuchCards(): void
    {
        # Arrange
        $client = static::createClient();

        # Act / Assert
        $client->request('POST', '/api/deck/draw/57');

        $this->assertResponseStatusCodeSame(500);

    }

    /**
     * Test method the game api route without game.
     */
    public function testBjApiNoGame(): void
    {
        # Arrange
        $client = static::createClient();

        $client->request('GET', '/api/game');

        # Assert
        $this->assertResponseStatusCodeSame(400);

    }

    /**
     * Test method the game api route.
     */
    public function testBjApi(): void
    {
        # Arrange
        $client = static::createClient();

        $client->request('POST', '/game/init', ['name' => 'tester']);

        # Act
        $client->request('GET', '/api/game');

        # Assert
        $this->assertResponseIsSuccessful();
    }

}
