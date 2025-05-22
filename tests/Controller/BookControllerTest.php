<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for BookController.
 */
class BookControllerTest extends WebTestCase
{
    /**
     * Test that all routes works.
     */
    public function testBookControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/library', '/library/create', '/library/show'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * Test create book POST method
     */
    public function testCreateBookPost(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('GET', '/library/create');

        # Act
        $client->submitForm('Lägg till bok', [
            'book_title' => 'Lord of the rings',
            'book_isbn' => '123456789123',
            'book_author' => 'Tolkien'
        ]);

        # Assert
        $this->assertResponseRedirects('/library/show');
    }
}
