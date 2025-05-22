<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Exception;

class JsonLibrary extends AbstractController
{
    #[Route("/api/library/books", name: "api_library", methods: ['GET'])]
    public function apiLibrary(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                'image' => $book->getImage()
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
    #[Route("/api/library/book/{isbn}", name: "api_library_isbn", methods: ['GET'])]
    public function apiBookByIsbn(BookRepository $bookRepository, string $isbn): Response
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return new JsonResponse(['error' => 'Ingen bok hittades med det isbn'], 404);
        }

        $data = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                'image' => $book->getImage()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;

    }


}
