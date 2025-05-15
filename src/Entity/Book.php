<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a book in the library database.
 */
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    /**
     * Id for the book.
     * Unique and automatically generated.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id = null;

    /**
     * The title of the book.
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * The isbn of the book.
     */
    #[ORM\Column(length: 255)]
    private ?string $isbn = null;

    /**
     * The author of the book.
     */
    #[ORM\Column(length: 255)]
    private ?string $author = null;

    /**
     * Image filename representing the book cover.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * Get the ID of the book
     * @return int|null The ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the book.
     * @return string|null The book title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the book title.
     * @param string $title The book title.
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the isbn of the book
     * @return string|null The isbn.
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * Set the isbn of the book.
     * @param string $isbn The isbn.
     * @return $this
     */
    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get the author of the book.
     * @return string|null The author.
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Set the author of the book.
     * @param string $author The author.
     * @return $this
     */
    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the image filename of the book.
     * @return string|null The image filename.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set the image filename for the book.
     * @param string $image The filename.
     * @return $this
     */
    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
