<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for ApiController.
 */
class ApiControllerTest extends WebTestCase
{
    /**
     * Test that all routes works.
     */
    public function testApiControllerRoute(): void
    {
        # Arrange
        $client = static::createClient();

        # Act
        $client->request('GET', '/api');

        # Assert
        $this->assertResponseIsSuccessful();

    }
}
