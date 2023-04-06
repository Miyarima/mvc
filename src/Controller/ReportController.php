<?php

namespace App\Controller;

use App\Deck\Card;
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
        return $this->render('index.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
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

        return $this->render('lucky.html.twig', $data);
    }

    #[Route("/api/quote", name: "quote")]
    public function quote(): Response
    {
        $number = random_int(0, 100);

        if ($number < 13) {
            $quote = "Life is not a game of luck. If you wanna win, work hard. - Sora";
        } elseif ($number < 25) {
            $quote = "If you cant do something, then dont. Focus on what you can do.  - Shiroe";
        } elseif ($number < 38) {
            $quote = "To know sorrow is not terrifying. What is terrifuing is to knw you cant go back to the happiness you could have. - Rangiku";
        } elseif ($number < 50) {
            $quote = "Ill leave tomorrows problems to tomorrows me.  - Saitama";
        } elseif ($number < 63) {
            $quote = "Knowing what it feels to be in pain, is exactly why we try to be kind to others.  - Jiraiya";
        } elseif ($number < 75) {
            $quote = "Reject common sense to make the impossible possible.  - Simon";
        } elseif ($number < 88) {
            $quote = "You cant sit around envying other peoples worlds. You have to go out and change your own.  - Shinichi";
        } else {
            $quote = "Who decides limits? And based on what? You said you worked hard? Well, maybe you need to work a little harder. Is that really the limit of your strength? Could you of tomorrow beat you today? Instead of giving in, move forward.  - Saitama";
        }

        $data = [
            'quote' => $quote,
            'date' => date("Y-m-d"),
            'timestamp' => date("H:i:s")
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/card", name: "cards", methods: ['GET'])]
    public function card(
        SessionInterface $session
    ): Response {
        // $session->set("removed_cards", []);

        return $this->render('cards/cards.html.twig');
    }

    #[Route("/card/deck", name: "show_deck", methods: ['GET'])]
    public function showDeck(
        SessionInterface $session
    ): Response
    {
        // $deck = new DeckOfCards();
        // for ($i = 1; $i <= 52; $i++) {
        //     $deck->add(new Card());
        // }

        $deck = $this->getDeck($session);
        if($deck === null) {
            $deck = $this->createDeck($session);
        }

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
    ): Response
    {   
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
        if($deck === null) {
            $deck = $this->createDeck($session);
        }

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
        if($deck === null) {
            $deck = $this->createDeck($session);
        }
        
        if ($number > $deck->getNumberCards()) {
            throw new \Exception("There are not that many cards left in the deck!");
        }

        // foreach($deck as $card) {
        //     echo $card . "<br>";
        // }
        // var_dump($deck);

        $card = $deck->getSpecificCard($number)->getName();
        $deck->removeCard($card);


        $this->removeAndStoreCard($card, $session);

        $data = [
            "card" => $card,
            "cardRemaning" => $deck->getNumberCards(),
        ];

        return $this->render('cards/draw_card.html.twig', $data);
    }
}
