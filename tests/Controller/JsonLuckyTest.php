<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for JsonLucky controller.
 */
class JsonLuckyTest extends WebTestCase
{
    /**
     * Test that all routes works.
     */
    public function testControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/api/lucky/number', '/api/quote'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }
}
