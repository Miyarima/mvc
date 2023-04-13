<?php

namespace App\Controller;

use App\Game\BlackJack;
use App\Traits\CreateDeck;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    use CreateDeck;

    #[Route("/blackjack", name: "black_jack", methods: ['GET'])]
    public function blackJack(SessionInterface $session): Response
    {   
        $session->set("playerCards", []);
        $session->set("houseCards", []);
        return $this->render('game/landing_page.html.twig');
    }

    #[Route("/blackjack/game", name: "start_black_jack", methods: ['GET'])]
    public function startBlackJack(
        SessionInterface $session
    ): Response {
        $blackJack = new BlackJack();

        if ($session->get("removed_cards") !== null) {
            $blackJack->setDeck($session->get("removed_cards"));
        }

        $blackJack->shuffleDeck();
        $deck = $blackJack->getDeck();

        $blackJack->setPlayer($session->get("playerCards"));

        $data = [
            "cards" => $blackJack->getPlayer(),
            "cardRemaning" => $deck->getNumberCards(),
            "draw" => $this->generateUrl('black_jack_draw')
        ];

        return $this->render('game/game.html.twig', $data);
    }

    #[Route("/blackjack/game/draw", name: "black_jack_draw", methods: ['POST'])]
    public function draw(
        SessionInterface $session
    ): Response {
        $deck = $this->getDeck($session);
        $randInt = random_int(0, $deck->getNumberCards()-1);
        $card = $deck->getSpecificCard($randInt)->getName();
        $this->drawnPlayerCards($card, $session);

        return $this->redirectToRoute('start_black_jack');
    }

}
