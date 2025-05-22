<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for JsonLibrary controller.
 */
class JsonLibraryTest extends WebTestCase
{
    /**
     * Test that all routes works.
     */
    public function testControllerGetRoute(): void
    {
        # Arrange
        $client = static::createClient();

        # Act
        $client->request('GET', '/api/library/books');

        # Assert            
        $this->assertResponseIsSuccessful();

    }

    /**
     * Test route with isbn.
     * Create Book with EntityManager.
     */
    public function testIsbnRoute(): void
    {
        # Arrange
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        $book = new Book();
        $book->setIsbn('12345');
        $book->setTitle('Test Book Title');
        $book->setAuthor('Test Author');

        $entityManager->persist($book);
        $entityManager->flush();

        # Act
        $client->request('GET', '/api/library/book/12345');

        # Assert
        $this->assertResponseIsSuccessful();
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
