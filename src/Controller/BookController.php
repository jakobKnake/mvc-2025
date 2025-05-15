<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for the library in kmom05.
 */
final class BookController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig');
    }
    #[Route('/library/create', name: 'book_create_get', methods: ['GET'])]
    public function createBook(): Response
    {
        return $this->render('book/create.html.twig');
    }
    #[Route('/library/create', name: 'book_create_post', methods: ['POST'])]
    public function createBookPost(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $title = $request->request->get('book_title');
        $isbn = $request->request->get('book_isbn');
        $author = $request->request->get('book_author');

        $book = new Book();

        $book->setTitle($title)
            ->setIsbn($isbn)
            ->setAuthor($author)
            ->setImage('default-book.jpg');

        $entityManager->persist($book);

        $entityManager->flush();

        $this->addFlash('success', 'Du lade till en bok med titeln:'.$title);

        return $this->redirectToRoute('book_show_all');
    }
    #[Route('/library/show', name: 'book_show_all')]
    public function showAllBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('book/show_all.html.twig', $data);
    }
    #[Route('/library/show/{id}', name: 'book_by_id')]
    public function showBookById(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            $this->addFlash('error', 'Ingen bok hittades med det id'.$id);
            return $this->redirectToRoute('book_show_all');
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/show_book.html.twig', $data);
    }
    #[Route('/library/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }
        $title = $book->getTitle();

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'Boken "' . $title . '" har tagits bort.');

        return $this->redirectToRoute('book_show_all');
    }
    #[Route('/library/update/{id}', name: 'book_update_get', methods: ['GET'])]
    public function updateProduct(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/update.html.twig', $data);
    }
    #[Route('/library/update/{id}', name: 'book_update_post', methods: ['POST'])]
    public function updateProductPost(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $newTitle = $request->request->get('book_title');
        $newIsbn = $request->request->get('book_isbn');
        $newAuthor = $request->request->get('book_author');

        $book->setTitle($newTitle)
            ->setIsbn($newIsbn)
            ->setAuthor($newAuthor);

        $entityManager->flush();

        $this->addFlash('success', 'Boken har uppdaterats!');

        return $this->redirectToRoute('book_by_id', ['id' => $id]);
    }

    #[Route('/library/reset', name: 'library_reset')]
    public function setUpLibrary(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $query = $entityManager->createQuery('DELETE FROM App\Entity\Book b');

        $query->execute();

        $book1 = new Book();
        $book1->setTitle('Bibeln')
                ->setIsbn('9789189897649')
                ->setAuthor('Gud')
                ->setImage('bible.jpg');

        $book2 = new Book();
        $book2->setTitle('From Buddha to Jesus')
                ->setIsbn('9781854249562')
                ->setAuthor('Steve Cioccolanti')
                ->setImage('buddha.jpg');

        $book3 = new Book();
        $book3->setTitle('The Return of the Gods')
                ->setIsbn('9781636411422')
                ->setAuthor('Jonathan Cahn')
                ->setImage('return.jpg');

        $entityManager->persist($book1);
        $entityManager->persist($book2);
        $entityManager->persist($book3);

        $entityManager->flush();

        $this->addFlash('success', 'Bibblans databas har återställts! Lade till de 3 original böckerna.');

        return $this->redirectToRoute('library');
    }
}
