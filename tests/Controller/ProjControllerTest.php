<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for ProjController.
 */
class ProjControllerTest extends WebTestCase
{
    /**
     * Test that all GET routes have successful response.
     */
    public function testProjControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/proj', '/proj/loggin', '/proj/create'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * Test the logout route
     */
    public function testLogOutRoute(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('GET', '/proj/logout');

        # Assert
        $this->assertResponseRedirects('/proj');
    }
    
}
