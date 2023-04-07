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

class ApiController extends AbstractController
{
    use CreateDeck;

    #[Route("/api", name: "api", methods: ['GET'])]
    public function api(): Response
    {
        return $this->render('api/api.html.twig');
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

    #[Route("/api/deck", name: "api_show_deck", methods: ['GET'])]
    public function showApiDeck(
        SessionInterface $session
    ): Response {
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

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_deck_get", methods: ['GET'])]
    public function apiShuffleDeck(
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

        return $this->apiShuffleDeckPost($cardNames);
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_deck_post", methods: ['POST'])]
    public function apiShuffleDeckPost($cardNames): Response
    {
        shuffle($cardNames);

        $data = [
            "deck" => $cardNames,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_draw_card_get", methods: ['GET'])]
    public function apiDrawCard(
        SessionInterface $session
    ): Response {
        $deck = $this->getDeck($session);
        if($deck === null) {
            $deck = $this->createDeck($session);
        }

        return $this->apiDrawCardPost($deck, $session);
    }

    #[Route("/api/deck/draw", name: "api_draw_card_post", methods: ['POST'])]
    public function apiDrawCardPost(
        $deck,
        SessionInterface $session
    ): Response {

        $key = random_int(0, $deck->getNumberCards()-1);
        $card = $deck->getSpecificCard($key)->getName();
        $deck->removeCard($card);

        $this->removeAndStoreCard($card, $session);

        $data = [
            "card" => $card,
            "cardRemaning" => $deck->getNumberCards(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_draw_specific_card", methods: ['GET'])]
    public function apiDrawSpecificCard(
        int $number,
        SessionInterface $session
    ): Response {
        $deck = $this->getDeck($session);
        if($deck === null) {
            $deck = $this->createDeck($session);
        }

        return $this->apiDrawSpecificCardPost($deck, $number, $session);
    }

    #[Route("/api/deck/draw", name: "api_draw_specific_card_post", methods: ['POST'])]
    public function apiDrawSpecificCardPost(
        $deck,
        $number,
        SessionInterface $session
    ): Response {

        if ($number > $deck->getNumberCards()) {
            throw new \Exception("There are not that many cards left in the deck!");
        }

        $card = $deck->getSpecificCard($number)->getName();
        $deck->removeCard($card);


        $this->removeAndStoreCard($card, $session);

        $data = [
            "card" => $card,
            "cardRemaning" => $deck->getNumberCards(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
