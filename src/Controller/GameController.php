<?php

namespace App\Controller;

use App\Game\BlackJack;
use App\Traits\CreateDeck;
use App\Traits\DrawnCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

//TODO GÖR SÅ ATT KORT FÖRSVINNER NÄR MAN DRAR DEM

class GameController extends AbstractController
{
    use CreateDeck;

    use DrawnCards;

    #[Route("/game", name: "black_jack", methods: ['GET'])]
    public function blackJack(SessionInterface $session): Response
    {
        $session->set("playerCards", []);
        $session->set("houseCards", []);
        $session->set("removed_cards", []);
        $session->set("gameStatus", "");
        $session->set("message", "");
        return $this->render('game/landing_page.html.twig');
    }

    #[Route("/game/doc", name: "doc_black_jack", methods: ['GET'])]
    public function blackJackDock(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/blackjack", name: "start_black_jack", methods: ['GET'])]
    public function startBlackJack(
        SessionInterface $session
    ): Response {
        $blackJack = new BlackJack();

        $blackJack->setDeck([]);
        if ($session->get("removed_cards") !== []) {
            $blackJack->setDeck($session->get("removed_cards"));
        }

        $deck = $blackJack->getDeck();

        $blackJack->setPlayer($session->get("playerCards"));
        $blackJack->setHouse($session->get("houseCards"));

        if ($blackJack->housePoints() > 0) {
            $session->set("message", $blackJack->winner());
            $session->set("gameStatus", "Done");
        }

        $data = [
            "cardRemaning" => $deck->getNumberCards(),
            "playerDraw" => $this->generateUrl('black_jack_draw'),
            "houseDraw" => $this->generateUrl('black_jack_house_draw'),
            "player" => $blackJack->getPlayer(),
            "house" => $blackJack->getHouse(),
            "playerPoints" => $blackJack->playerPoints(),
            "housePoints" => $blackJack->housePoints(),
            "gameStatus" => $session->get("gameStatus"),
            "message" => $session->get("message")
        ];

        return $this->render('game/game.html.twig', $data);
    }

    #[Route("/game/blackjack/draw", name: "black_jack_draw", methods: ['POST'])]
    public function draw(
        SessionInterface $session
    ): Response {
        $session->set("gameStatus", "Draw");

        $blackJack = new BlackJack();

        $blackJack->setDeck([]);
        if ($session->get("removed_cards") !== []) {
            $blackJack->setDeck($session->get("removed_cards"));
        }

        $card = $blackJack->playerDraw();
        $this->removeAndStoreCard($card, $session);
        $this->drawnPlayerCards($card, $session);

        return $this->redirectToRoute('start_black_jack');
    }

    #[Route("/game/blackjack/house", name: "black_jack_house_draw", methods: ['POST'])]
    public function houseDraw(
        SessionInterface $session
    ): Response {
        $session->set("gameStatus", "Stand");

        $blackJack = new BlackJack();

        $blackJack->setDeck([]);
        if ($session->get("removed_cards") !== []) {
            $blackJack->setDeck($session->get("removed_cards"));
        }

        $blackJack->setPlayer($session->get("playerCards"));
        $blackJack->setHouse($session->get("houseCards"));

        $cards = $blackJack->houseDraw();

        foreach ($cards as $card) {
            $this->removeAndStoreCard($card, $session);
            $this->drawnHouseCards($card, $session);
        }

        return $this->redirectToRoute('start_black_jack');
    }

    #[Route("/game/blackjack/restart", name: "black_jack_restart", methods: ['GET'])]
    public function restart(
        SessionInterface $session
    ): Response {
        $session->set("playerCards", []);
        $session->set("houseCards", []);
        $session->set("removed_cards", []);
        $session->set("gameStatus", "");
        $session->set("message", "");

        return $this->redirectToRoute('start_black_jack');
    }
}
