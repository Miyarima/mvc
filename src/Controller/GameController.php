<?php

namespace App\Controller;

use App\Deck\Card;
use App\Deck\CardHand;
use App\Deck\DeckOfCards;

use App\Traits\CreateDeck;

// use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    use CreateDeck;

    #[Route("/blackjack", name: "black_jack", methods: ['GET'])]
    public function blackJack(): Response {
        return $this->render('game/landing_page.html.twig');
    }

    #[Route("/blackjack/game", name: "start_black_jack", methods: ['GET'])]
    public function startBlackJack(
        SessionInterface $session
    ): Response {
        // $deck = $this->getDeck($session);
        // if($deck === null) {
        //     $deck = $this->createDeck($session);
        // }
        $deck = $this->getDeck($session);


        // if ($number > $deck->getNumberCards()) {
        //     throw new \Exception("There are not that many cards left in the deck!");
        // }

        $hand = new CardHand();

        for ($i = 0; $i < 5; $i++) {
            $key = random_int(0, $deck->getNumberCards()-1);
            $card = $deck->getSpecificCard($key)->getName();
            $hand->add($deck->getSpecificCard($key));
            // $deck->removeCard($card);
            // $this->removeAndStoreCard($card, $session);
        }

        $cardNames = [];
        foreach($hand->getCards() as $card) {
            $cardNames[] = $card->getName();
        }

        $data = [
            "cards" => $cardNames,
            "cardRemaning" => $deck->getNumberCards(),
        ];

        return $this->render('game/game.html.twig', $data);
    }

}