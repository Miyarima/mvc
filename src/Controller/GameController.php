<?php

namespace App\Controller;

use App\Game\BlackJack;
use App\Traits\CreateDeck;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

//TODO GÖR SÅ ATT KORT FÖRSVINNER NÄR MAN DRAR DEM

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

        $blackJack->setDeck([]);
        if ($session->get("removed_cards") !== null) {
            $blackJack->setDeck($session->get("removed_cards"));
        }

        $blackJack->shuffleDeck();
        $deck = $blackJack->getDeck();

        $blackJack->setPlayer($session->get("playerCards"));
        $blackJack->setHouse($session->get("houseCards"));

        // var_dump($session->get("housePoints"));

        $data = [
            "cardRemaning" => $deck->getNumberCards(),
            "playerDraw" => $this->generateUrl('black_jack_draw'),
            "houseDraw" => $this->generateUrl('black_jack_house_draw'),
            "player" => $blackJack->getPlayer(),
            "house" => $blackJack->getHouse(),
            "playerPoints" => $blackJack->playerPoints(),
            "housePoints" => $blackJack->housePoints()
        ];

        return $this->render('game/game.html.twig', $data);
    }

    #[Route("/blackjack/game/draw", name: "black_jack_draw", methods: ['POST'])]
    public function draw(
        SessionInterface $session
    ): Response {
        //TODO SLUMPA BEHÖVS INTE OM DECKEN ÄR SHUFFLAD
        //TODO DE MEST HÄR BORDE VARA I BLACKJACK KLASSEN
        $deck = $this->getDeck($session);
        $randInt = random_int(0, $deck->getNumberCards()-1);
        $card = $deck->getSpecificCard($randInt)->getName();
        $this->drawnPlayerCards($card, $session);

        return $this->redirectToRoute('start_black_jack');
    }

    #[Route("/blackjack/game/house", name: "black_jack_house_draw", methods: ['POST'])]
    public function houseDraw(
        SessionInterface $session
    ): Response {
        //TODO SLUMPA BEHÖVS INTE OM DECKEN ÄR SHUFFLAD
        //TODO DE MEST HÄR BORDE VARA I BLACKJACK KLASSEN
        $blackJack = new BlackJack();

        $blackJack->setDeck([]);
        if ($session->get("removed_cards") !== null) {
            $blackJack->setDeck($session->get("removed_cards"));
        }

        $deck = $blackJack->getDeck();

        $blackJack->setPlayer($session->get("playerCards"));
        $blackJack->setHouse($session->get("houseCards"));
        
        while ($blackJack->playerPoints() >= $blackJack->housePoints()) {
            $randInt = random_int(0, $deck->getNumberCards()-1);
            $card = $deck->getSpecificCard($randInt)->getName();
            $this->drawnHouseCards($card, $session);
            $blackJack->setHouse($session->get("houseCards"));
        }
        // exit();
        // $session->set("housePoints", $blackJack->housePoints());

        return $this->redirectToRoute('start_black_jack');
    }

    //? 6 (2) varje kort är 7 | 42
    //? 5 (2) varje kort är 6 | 30
    //? 4 (2) varje kort är 5 | 20
    //? 3 (2) varje kort är 4 | 12
    //? 2 (2) varje kort är 3 | 6
    //? 1 (2) varje kort är 2 | 2
}
