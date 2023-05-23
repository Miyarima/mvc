<?php

namespace App\Controller;

use App\Adventure\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdventureController extends AbstractController
{   
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

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

    #[Route('/proj/game/handle', name: 'handle_adventure', methods: ['POST'])]
    public function handelAdventure(
        Request $request,
        SessionInterface $session
    ): Response {
        $game = $this->game;

        $command = "";
        foreach ($request->request as $key => $value) {
            $command = strtolower($value);
        }

        $pos = $session->get("position");

        [$action, $answer] = $game->command($command, $pos);

        if($action === "go" && $answer !== "You can't") {
            return $this->redirectToRoute($answer);
        } 
        
        if ($action === "go") {
            $answer = ["$answer $command from here!"];
        } elseif ($action === "inventory") {
            $answer = array_map(
                fn ($item) => "{$item[0]} {$item[1]} {$item[2]}",
                $answer
            );
        } elseif ($action === "house" || $action === "path") {
            $answer = array_map(
                fn ($item) => $item[0],
                $answer
            );
        }

        return $this->render('adventure/adventure.html.twig', [
            "text" => $answer,
            "img" => $pos,
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/house", name: "house_adventure", methods: ['GET'])]
    public function startAdventure(
        SessionInterface $session
    ): Response {
        $session->set("position", "house");

        $startText = ["After dreaming of my blacksmith grandfather, I inherited his legacy. With honor and responsibility, I embarked on my own adventure, forging a path filled with challenges and surprises. Each hammer strike honored his unique knowledge."];

        return $this->render('adventure/adventure.html.twig', [
            "text" => $startText,
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/path", name: "path_adventure", methods: ['GET'])]
    public function pathAdventure(
        SessionInterface $session
    ): Response {
        $session->set("position", "path");

        $startText = [
            "Emotions filled the air as I left, carrying your legacy within. The cottage stood silent, but I felt both weight and excitement. Your knowledge lives on, and I'll honor it through my own adventure.",
        ];

        return $this->render('adventure/adventure.html.twig', [
            "text" => $startText,
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/cave", name: "cave_adventure", methods: ['GET'])]
    public function caveAdventure(
        SessionInterface $session
    ): Response {
        $session->set("position", "cave");

        $startText = [
            "As I enter the cave, following your path, I seek iron for my craft. Your inspiring stories push me to explore and overcome challenges. With your knowledge in my heart, each step and strike honors you in this mysterious realm of possibilities.",
        ];

        return $this->render('adventure/adventure.html.twig', [
            "text" => $startText,
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/dungeon", name: "dungeon_adventure", methods: ['GET'])]
    public function dungeonAdventure(
        SessionInterface $session
    ): Response {
        $session->set("position", "dungeon");

        $startText = [
            "I know the journey won't be easy. The dungeon is filled with perils and trials designed to test my strength and endurance. But I am ready to fight for the treasure and overcome the monster guarding it. Every step I take and every sword strike I make is a tribute to you and the knowledge you have imparted.",
        ];

        return $this->render('adventure/adventure.html.twig', [
            "text" => $startText,
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }
}
