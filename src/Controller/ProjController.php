<?php

namespace App\Controller;

use App\Card\GameLogic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for the project.
 */
class ProjController extends AbstractController
{
    #[Route("/proj", name: "home_bet")]
    public function homeBet(): Response
    {
        return $this->render('proj/home.html.twig');
    }
    #[Route("/proj/loggin", name: "logg_in", methods:['GET'])]
    public function logInGet(): Response
    {
        return $this->render('proj/log_in.html.twig');
    }

}
