<?php

namespace App\Controller;

use App\Deck\Card;
use App\Deck\CardHand;
use App\Deck\DeckOfCards;

use App\Traits\CreateDeck;

use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    use CreateDeck;

    #[Route("/", name: "index")]
    public function index(): Response
    {
        return $this->render('base/index.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('base/about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('base/report.html.twig');
    }

    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);

        if ($number > 50) {
            $picture = 'img/me.jpg';
        } else {
            $picture = 'img/me.jpg';
        }

        $data = [
            'number' => $number,
            'img' => $picture
        ];

        return $this->render('base/lucky.html.twig', $data);
    }

    #[Route("/card", name: "cards", methods: ['GET'])]
    public function card(): Response
    {
        return $this->render('cards/cards.html.twig');
    }

    #[Route("/card/deck", name: "show_deck", methods: ['GET'])]
    public function showDeck(
        SessionInterface $session
    ): Response {
        $deck = $this->getDeck($session);

        $cardNames = [];
        foreach($deck->getCards() as $card) {
            $cardNames[] = $card->getName();
        }

        $data = [
            "deck" => $cardNames,
        ];

        return $this->render('cards/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffle_deck", methods: ['GET'])]
    public function shuffleDeck(
        SessionInterface $session
    ): Response {
        $session->set("removed_cards", []);

        $deck = new DeckOfCards();
        for ($i = 1; $i <= 52; $i++) {
            $deck->add(new Card());
        }

        $cardNames = [];
        foreach($deck->getCards() as $card) {
            $cardNames[] = $card->getName();
        }
        shuffle($cardNames);

        $data = [
            "deck" => $cardNames,
        ];

        return $this->render('cards/deck.html.twig', $data);
    }

 #[Route("/card/deck/draw", name: "draw_card", methods: ['GET'])]
    public function drawCard(
        SessionInterface $session
    ): Response {
        $deck = $this->getDeck($session);

        $key = random_int(0, $deck->getNumberCards()-1);
        $card = $deck->getSpecificCard($key)->getName();
        $deck->removeCard($card);

        $this->removeAndStoreCard($card, $session);

        $data = [
            "card" => $card,
            "cardRemaning" => $deck->getNumberCards(),
        ];

        return $this->render('cards/draw_card.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "draw_specific_card", methods: ['GET'])]
    public function drawSpecificCard(
        int $number,
        SessionInterface $session
    ): Response {
        $deck = $this->getDeck($session);

        if ($number > $deck->getNumberCards()) {
            throw new \Exception("There are not that many cards left in the deck!");
        }

        $hand = new CardHand();

        for ($i = 0; $i < $number; $i++) {
            $key = random_int(0, $deck->getNumberCards()-1);
            $card = $deck->getSpecificCard($key)->getName();
            $hand->add($deck->getSpecificCard($key));
            $deck->removeCard($card);
            $this->removeAndStoreCard($card, $session);
        }

        $cardNames = [];
        foreach($hand->getCards() as $card) {
            $cardNames[] = $card->getName();
        }

        $data = [
            "cards" => $cardNames,
            "cardRemaning" => $deck->getNumberCards(),
        ];

        return $this->render('cards/draw_cards_many.html.twig', $data);
    }
}
