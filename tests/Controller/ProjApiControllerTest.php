<?php

namespace App\Controller;

use App\Entity\Project\User;
use App\Entity\Project\History;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for ProjApiController.
 */
class ProjApiControllerTest extends WebTestCase
{
    /**
     * Test that GET routes without sessions have successful response.
     */
    public function testProjApiControllerGetRoutes(): void
    {

        # Arrange
        $client = static::createClient();
        $routes = ['/proj/api', '/proj/api/data'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }

}
