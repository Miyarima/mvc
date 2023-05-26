<?php

namespace App\Controller;

use App\Adventure\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdventureApiController extends AbstractController
{
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    #[Route("/proj/api", name: "project_api", methods: ['GET'])]
    public function project(): Response
    {
        $game = $this->game;
        $game->resetGame();

        return $this->render('adventure/api.html.twig');
    }

    #[Route("/proj/api/message/path", name: "path_message_api", methods: ['GET'])]
    public function pathMessageApi(): Response {
        $game = $this->game;

        $message = $game->message("path");
        $look = $game->command("look", "path");

        $data = [
            "message" => $message,
            "look" => $look
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/message/house", name: "house_message_api", methods: ['GET'])]
    public function houseMessageApi(): Response {
        $game = $this->game;

        $message = $game->message("house");
        $look = $game->command("look", "house");

        $data = [
            "message" => $message,
            "look" => $look
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/message/cave", name: "cave_message_api", methods: ['GET'])]
    public function caveMessageApi(): Response {
        $game = $this->game;

        $message = $game->message("cave");
        $look = $game->command("look", "cave");

        $data = [
            "message" => $message,
            "look" => $look
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/message/dungeon", name: "dungeon_message_api", methods: ['GET'])]
    public function dungeonMessageApi(): Response {
        $game = $this->game;

        $message = $game->message("dungeon");
        $look = $game->command("look", "dungeon");

        $data = [
            "message" => $message,
            "look" => $look
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    // #[Route('/proj/game/handle', name: 'handle_adventure', methods: ['POST'])]
    // public function handelAdventure(
    //     Request $request,
    //     SessionInterface $session
    // ): Response {
    //     $game = $this->game;

    //     $command = "";
    //     foreach ($request->request as $value) {
    //         $command = strtolower($value);
    //     }

    //     $pos = $session->get("position");

    //     [$action, $answer] = $game->command($command, $pos);

    //     if($action === "go" && $answer !== "You can't") {
    //         return $this->redirectToRoute($answer);
    //     }

    //     if ($action === "go") {
    //         $answer = ["$answer $command from here!"];
    //     } elseif ($action === "inventory") {
    //         $answer = array_map(
    //             fn ($item) => "{$item[0]} {$item[1]} {$item[2]}",
    //             $answer
    //         );
    //     } elseif ($action === "house" || $action === "path" ||
    //                 $action === "cave" || $action === "dungeon") {
    //         $answer = array_map(
    //             fn ($item) => $item[0],
    //             $answer
    //         );
    //     }
}
