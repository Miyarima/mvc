<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait DrawnCards
{
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
