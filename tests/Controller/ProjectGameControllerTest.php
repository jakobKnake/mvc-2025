<?php

namespace App\Controller;

use App\Entity\Project\User;
use App\Entity\Project\History;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for ProjectGameController.
 */
class ProjectGameControllerTest extends WebTestCase
{
    /**
     * Test accessing routes without being logged in.
     */
    public function testRoutesNoUser(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/proj/init_game', '/proj/bets', '/proj/play',];
        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseRedirects('/proj/loggin');
        }

        $postRoutes = ['/proj/bets', '/proj/hit', '/proj/stand',
            '/proj/split', '/proj/double'];

        # Act / Assert
        foreach ($postRoutes as $route) {
            $client->request('POST', $route);
            $this->assertResponseRedirects('/proj/loggin');
        }

    }

    /**
     * Test when logged in get routes.
     */
    public function testWithLoggedInUser(): void
    {
        # Arrange
        $client = static::createClient();

        $client->request('POST', '/proj/loggin', [
            'username' => 'testKonto',
            'password' => 'test123'
        ]);
        
        $client->followRedirects();

        # Act / Assert
        $client->request('GET', '/proj/init_game');
        $this->assertResponseIsSuccessful();

        # Act / Assert
        $client->request('POST', '/proj/init');
        $this->assertResponseIsSuccessful();

        # Act / Assert
        $client->request('POST', '/proj/bets');
        $this->assertResponseIsSuccessful();

        # Act / Assert
        $client->request('GET', '/proj/play');
        $this->assertResponseIsSuccessful();

        $postRoutes = ['/proj/hit', '/proj/split', '/proj/double'];

        # Act / Assert
        foreach ($postRoutes as $route) {
            $client->request('POST', $route);
            $this->assertResponseIsSuccessful();
        }
        
        
    }
}
