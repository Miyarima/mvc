<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/proj/game/handel', name: 'handel_adventure', methods: ['POST'])]
    public function handelAdventure(
        Request $request,
        SessionInterface $session
    ): Response {
        $form = "";
        foreach ($request->request as $key => $value) {
            $form = strtolower($value);
        }

        $pos = $session->get("position");

        $text = "";
        if ($form == "help") {
            $text = [
                "Theses are the things you can do:",
                "Use the 'go' command to move in different directions e.g south.",
                "Inventory",
                "Pickup",
                "Help"
            ];
        } elseif ($form == "go north") {
            if ($pos == "house") {
                return $this->redirectToRoute('path_adventure');
            } elseif ($pos == "path") {
                return $this->redirectToRoute('dungeon_adventure');
            }

            return $this->render('adventure/adventure.html.twig', [
                "text" => ["You can't go North from here."],
                "img" => $session->get("position"),
                "commandHandler" => $this->generateUrl('handel_adventure'),
            ]);
        } elseif ($form == "go south") {
            if ($pos == "path") {
                return $this->redirectToRoute('start_adventure');
            } elseif ($pos == "dungeon") {
                return $this->redirectToRoute('path_adventure');
            }

            return $this->render('adventure/adventure.html.twig', [
                "text" => ["You can't go South from here."],
                "img" => $session->get("position"),
                "commandHandler" => $this->generateUrl('handel_adventure'),
            ]);
        } elseif ($form == "go west") {
            if ($pos == "path") {
                return $this->redirectToRoute('cave_adventure');
            }

            return $this->render('adventure/adventure.html.twig', [
                "text" => ["You can't go West from here."],
                "img" => $session->get("position"),
                "commandHandler" => $this->generateUrl('handel_adventure'),
            ]);
        } elseif ($form == "go east") {
            if ($pos == "cave") {
                return $this->redirectToRoute('path_adventure');
            }

            return $this->render('adventure/adventure.html.twig', [
                "text" => ["You can't go East from here."],
                "img" => $session->get("position"),
                "commandHandler" => $this->generateUrl('handel_adventure'),
            ]);
        } else {
            $text = ["I'm not familiar with your usage of '$form'"];
        }

        return $this->render('adventure/adventure.html.twig', [
            "text" => $text,
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handel_adventure'),
        ]);
    }

    #[Route("/proj/game/start", name: "start_adventure", methods: ['GET'])]
    public function startAdventure(
        SessionInterface $session
    ): Response {
        $session->set("position", "house");

        $startText = ["After dreaming of my blacksmith grandfather, I inherited his legacy. With honor and responsibility, I embarked on my own adventure, forging a path filled with challenges and surprises. Each hammer strike honored his unique knowledge."];

        return $this->render('adventure/adventure.html.twig', [
            "text" => $startText,
            "img" => $session->get("position"),
            "commandHandler" => $this->generateUrl('handel_adventure'),
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
            "commandHandler" => $this->generateUrl('handel_adventure'),
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
            "commandHandler" => $this->generateUrl('handel_adventure'),
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
            "commandHandler" => $this->generateUrl('handel_adventure'),
        ]);
    }
}
