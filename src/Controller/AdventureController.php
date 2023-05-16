<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class AdventureController extends AbstractController
{
    #[Route("/proj", name: "project", methods: ['GET'])]
    public function project(): Response
    {
        return $this->render('adventure/home.html.twig');
    }

    #[Route("/proj/about", name: "about_project", methods: ['GET'])]
    public function aboutProject(): Response
    {
        return $this->render('adventure/about.html.twig');
    }
}
