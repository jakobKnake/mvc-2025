<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for HomeController.
 */
class HomeControllerTest extends WebTestCase
{
    /**
     * Test that all routes works.
     */
    public function testHomeControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/', '/about', '/report', '/lucky', '/metrics'];

        # Act / Assert
        foreach($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }
        
    }
}