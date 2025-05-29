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
    public function testProjControllerRoute(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/proj', '/proj/loggin'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();

        }

    }
}
