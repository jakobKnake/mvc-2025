<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for CardGameController.
 */
class CardGameControllerTest extends WebTestCase
{
    /**
     * Test that all GET method routes works
     */
    public function testCardGameControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/card', '/card/deck', '/card/deck/shuffle', '/card/deck/draw', '/session'];

        # Act / Assert
        foreach($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }
        
    }
}