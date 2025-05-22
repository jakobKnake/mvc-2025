<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for BlackJackController.
 */
class BlackJackControllerTest extends WebTestCase
{
    /**
     * Test that all GET method routes works
     */
    public function testBlackJackControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/game', '/game/doc', '/game/init'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * Test the post init route.
     */
    public function testInitPost(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('GET', '/game/init');

        # Act
        $client->submitForm('do_it', [
            'name' => 'tester',
        ]);

        # Assert
        $this->assertResponseRedirects('/game/play');
    }

    /**
     * Test playerHit method
     */
    public function testHit(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('POST', '/game/init', ['name' => 'tester']);

        $client->request('GET', '/game/play');
        # Act
        $client->submitForm('Hit');

        $response = $client->getResponse();
        $location = $response->headers->get('Location');

        # Assert
        $this->assertTrue(
            $location === '/game/play' || $location === '/game/dealer'
        );
    }

    /**
     * Test playerStand method
     */
    public function testStand(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('POST', '/game/init', ['name' => 'tester']);

        $client->request('GET', '/game/play');

        # Act
        $client->submitForm('Stand');

        # Assert
        $this->assertResponseRedirects('/game/dealer');
    }

    /**
     * Test Hit and Stand redirects without game session.
     */
    public function testWithoutGameSessionPost(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/game/hit', '/game/stand'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('POST', $route);
            $this->assertResponseRedirects('/game/init');
        }
    }

    /**
     * Test playDealer method
     */
    public function testPlayDealer(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('POST', '/game/init', ['name' => 'tester']);

        # Act
        $client->request('GET', '/game/dealer');

        # Assert
        $this->assertResponseRedirects('/game/play');
    }

    /**
     * Test get methods without game session to redirect correct.
     */
    public function testWithoutGameSess(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/game/play', '/game/dealer'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseRedirects('/game/init');
        }
    }
}
