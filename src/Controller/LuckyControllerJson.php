<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api/lucky/number", methods: ['GET'])]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

       // return new JsonResponse($data);
       $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", methods: ['GET'])]
    public function getQuote(): Response
    {
        $quotes = [
            'Våga satsa för att vinnaaaaa.',
            'Nära skjuter ingen hare.',
            'Två klöver är bättre än en.',
            'Du kan alltid använda frugans uppgifter för att ta del av bonus.'
        ];
        $randomIdx = random_int(0, count($quotes) - 1);
        $displayQuote = $quotes[$randomIdx];

        $data = [
            'quote' => $displayQuote,
            'date' => date('Y-m-d'),
            'timestamp' => date('H:i:s')
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

}