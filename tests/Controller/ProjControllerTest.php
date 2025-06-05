<?php

namespace App\Controller;

use App\Entity\Project\User;
use App\Entity\Project\History;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for ProjController.
 */
class ProjControllerTest extends WebTestCase
{
    /**
     * Test that all GET routes have successful response.
     */
    public function testProjControllerRoutes(): void
    {
        # Arrange
        $client = static::createClient();
        $routes = ['/proj', '/proj/loggin', '/proj/create', '/proj/about', '/proj/about/database'];

        # Act / Assert
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * Test the logout route
     */
    public function testLogOutRoute(): void
    {
        # Arrange
        $client = static::createClient();
        $client->request('GET', '/proj/logout');

        # Assert
        $this->assertResponseRedirects('/proj');
    }

    /**
     * Test loggin with POST successful.
     * First create user then loggin.
     */
    public function testUserLogin(): void
    {
        $client = static::createClient();

        $client->request('POST', '/proj/create', [
            'username' => 'test',
            'password' => 'test123'
        ]);

        $client->request('POST', '/proj/loggin', [
            'username' => 'test',
            'password' => 'test123'
        ]);

        $this->assertResponseRedirects('/proj');
    }

    /**
     * Test failed loggin with POST.
     */
    public function testUserLoginFail(): void
    {
        $client = static::createClient();

        $client->request('POST', '/proj/loggin', [
            'username' => 'testKontoret',
            'password' => '123test'
        ]);

        $this->assertResponseRedirects('/proj/loggin');
    }

}
