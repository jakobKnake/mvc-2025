<?php

namespace App\Controller;

use App\Entity\Project\User;
use App\Entity\Project\History;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for ProfileController.
 */
class ProfileControllerTest extends WebTestCase
{
    /**
     * Test accessing routes without being logged in.
     */
    public function testNoUser(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/proj/show_user', '/proj/bank', '/proj/delete', '/proj/history'];
        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseRedirects('/proj/loggin');
        }

        $postRoutes = ['/proj/show_user', '/proj/bank'];

        # Act / Assert
        foreach ($postRoutes as $route) {
            $client->request('POST', $route);
            $this->assertResponseRedirects('/proj/loggin');
        }

    }

    /**
     * Test logging in and display user, bank and history
     */
    public function testRoutesFailedLoggedIn(): void
    {
        # Arrange
        $client = static::createClient();
        $projectEntityManager = static::getContainer()->get('doctrine')->getManager('project');
        $userRepository = $projectEntityManager->getRepository(User::class);

        $testUser = $userRepository->findOneBy(['username' => 'testKonto']);

        $client->loginUser($testUser);

        $client->request('GET', '/proj/show_user');
        $this->assertResponseRedirects('/proj/loggin');

    }


    /**
     * Test routees with created user and logged in.
     */
    public function testCreateUserAndNavigate(): void
    {
        # Arrange
        $client = static::createClient();

        $client->request('POST', '/proj/create', [
            'username' => 'testa',
            'password' => 'test123'
        ]);

        $client->request('POST', '/proj/loggin', [
            'username' => 'testa',
            'password' => 'test123'
        ]);

        $client->request('GET', '/proj/show_user');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/proj/bank');
        $this->assertResponseIsSuccessful();

        $client->request('GET', '/proj/history');
        $this->assertResponseIsSuccessful();
    }
}
