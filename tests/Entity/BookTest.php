<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for the Book entity.
 */
class BookTest extends TestCase
{
    /**
     * Test crating a Book instance.
     */
    public function testCreateBook(): void
    {
        $book = new Book();

        $this->assertInstanceOf(Book::class, $book);

    }

    /**
     * Test setting and getting title on book.
     */
    public function testTitle(): void
    {
        # Arrange
        $book = new Book();

        $title = "Harry Potter 1";

        # Act
        $book->setTitle($title);

        # Assert
        $this->assertEquals($title, $book->getTitle());

    }

    /**
     * Test setting and getting isbn.
     */
    public function testIsbn(): void
    {
        # Arrange
        $book = new Book();

        $isbn = "123456789";

        # Act
        $book->setIsbn($isbn);

        # Assert
        $this->assertEquals($isbn, $book->getIsbn());
    }

    /**
     * Test setting and getting author on book.
     */
    public function testAuthor(): void
    {
        # Arrange
        $book = new Book();

        $author = "JK Rowling";

        # Act
        $book->setAuthor($author);

        # Assert
        $this->assertEquals($author, $book->getAuthor());
    }

    /**
     * Test setting and getting image.
     */
    public function testImage(): void
    {
        # Arrange
        $book = new Book();

        $image = "harry.png";

        # Act
        $book->setImage($image);

        # Assert
        $this->assertEquals($image, $book->getImage());
    }

}
