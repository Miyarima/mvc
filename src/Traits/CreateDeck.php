<?php

namespace App\Traits;

use App\Deck\Card;
use App\Deck\DeckOfCards;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait CreateDeck
{
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

    private function drawnPlayerCards(string $card, SessionInterface $session): void
    {
        $drawn = $session->get("playerCards");
        $drawn[] = $card;
        $session->set("playerCards", $drawn);
    }

    private function drawnHouseCards(string $card, SessionInterface $session): void
    {
        $drawn = $session->get("houseCards");
        $drawn[] = $card;
        $session->set("houseCards", $drawn);
    }
}
