<?php

namespace App\Controller;

use App\Entity\Project\User;
use App\Entity\Project\History;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application; // LÄGG TILL DENNA
use Symfony\Component\Console\Input\ArrayInput; 

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
     * Test accessing routes with newly created user.
     */
    public function testNavigateGameWithUser(): void
    {
        # Arrange
        $client = static::createClient();

        $client->request('POST', '/proj/create', [
            'username' => 'tester',
            'password' => 'test123'
        ]);

        $client->request('POST', '/proj/loggin', [
            'username' => 'tester',
            'password' => 'test123'
        ]);

        $client->request('GET', '/proj/init_game');
        $this->assertResponseIsSuccessful();

        $client->request('POST', '/proj/init_game');
        $client->followRedirect();

        $client->request('GET', '/proj/bets');
        $this->assertResponseIsSuccessful();

        $client->followRedirects();
        $client->request('POST', '/proj/bets');
        


        $client->request('GET', '/proj/play');
        $this->assertResponseIsSuccessful();

        $client->followRedirects();
        $client->request('POST', '/proj/split');
        

        $client->request('GET', '/proj/play');
        $this->assertResponseIsSuccessful();
        

    }

}
