<?php

namespace App\Traits;

use App\Deck\Card;
use App\Deck\DeckOfCards;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait CreateDeck
{
    private function createDeck(SessionInterface $session): DeckOfCards
    {
        $deck = new DeckOfCards();
        for ($i = 1; $i <= 52; $i++) {
            $deck->add(new Card());
        }

        $removedCards = $session->get("removed_cards");
        if($removedCards !== null) {
            foreach($removedCards as $remove) {
                $deck->removeCard($remove);
            }
        }

        return $deck;
    }

    private function getDeck(SessionInterface $session): DeckOfCards
    {
        $removedCards = $session->get("removed_cards");

        $deck = new DeckOfCards();
        for ($i = 1; $i <= 52; $i++) {
            $deck->add(new Card());
        }

        if($removedCards !== null) {
            foreach($removedCards as $remove) {
                $deck->removeCard($remove);
            }
        }

        return $deck;
    }

    private function removeAndStoreCard(string $card, SessionInterface $session): void
    {
        $removedCards = $session->get("removed_cards");
        $removedCards[] = $card;
        $session->set("removed_cards", $removedCards);
    }
}
