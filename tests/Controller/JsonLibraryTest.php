<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for JsonLibrary controller.
 */
class JsonLibraryTest extends WebTestCase
{
    /**
     * Test that all routes works.
     */
    public function testControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/api/library/books', '/api/library/book/9789189897649'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * Test when no book is found with isbn
     */
    public function testNoIsbn(): void
    {
        # Arrange
        $client = static::createClient();

        # Act
        $client->request('GET', '/api/library/book/123456789988');

        # Assert
        $this->assertResponseStatusCodeSame(404);
    }
}
