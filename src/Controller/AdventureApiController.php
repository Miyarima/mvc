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
    public function pathMessageApi(): Response
    {
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

    #[Route("/proj/api/message/house", name: "house_message_api", methods: ['GET'])]
    public function houseMessageApi(): Response
    {
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

    #[Route("/proj/api/message/cave", name: "cave_message_api", methods: ['GET'])]
    public function caveMessageApi(): Response
    {
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

    #[Route("/proj/api/message/dungeon", name: "dungeon_message_api", methods: ['GET'])]
    public function dungeonMessageApi(): Response
    {
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

    #[Route("/proj/api/inventory", name: "inventory_api", methods: ['GET'])]
    public function inventoryApi(): Response
    {
        $game = $this->game;

        $inventory = $game->command("inventory", "house");

        $response = new JsonResponse($inventory);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/api/train/get", name: "cave_train_api", methods: ['GET'])]
    public function trainApi(): Response
    {
        return $this->trainApiPost("train", "cave");
    }

    #[Route("/proj/api/train", name: "cave_train_api_post", methods: ['POST'])]
    public function trainApiPost(
        string $command,
        string $pos
    ): Response {
        $game = $this->game;

        $train = $game->command($command, $pos);

        $response = new JsonResponse($train);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
