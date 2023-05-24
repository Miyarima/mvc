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
        $game = $this->game;
        $game->resetGame();

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
        foreach ($request->request as $value) {
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
        } elseif ($action === "house" || $action === "path" ||
                    $action === "cave" || $action === "dungeon") {
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
        $game = $this->game;

        $session->set("position", "house");

        $message = $game->message($session->get("position"));

        return $this->render('adventure/adventure.html.twig', [
            "text" => [$message],
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/path", name: "path_adventure", methods: ['GET'])]
    public function pathAdventure(
        SessionInterface $session
    ): Response {
        $game = $this->game;

        $session->set("position", "path");

        $message = $game->message($session->get("position"));

        return $this->render('adventure/adventure.html.twig', [
            "text" => [$message],
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/cave", name: "cave_adventure", methods: ['GET'])]
    public function caveAdventure(
        SessionInterface $session
    ): Response {
        $game = $this->game;

        $session->set("position", "cave");

        $message = $game->message($session->get("position"));

        return $this->render('adventure/adventure.html.twig', [
            "text" => [$message],
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }

    #[Route("/proj/game/dungeon", name: "dungeon_adventure", methods: ['GET'])]
    public function dungeonAdventure(
        SessionInterface $session
    ): Response {
        $game = $this->game;

        $session->set("position", "dungeon");

        $message = $game->message($session->get("position"));

        return $this->render('adventure/adventure.html.twig', [
            "text" => [$message],
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handle_adventure'),
        ]);
    }
}
