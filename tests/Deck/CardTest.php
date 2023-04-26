<?php

namespace App\Deck;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct a Card object without arguments and check default values.
     */
    public function testCreateCardCheckDefaultValues(): void
    {
        $card = new Card();
        $this->assertInstanceOf("\App\Deck\Card", $card);
        $res = $card->getName();
        $exp = "";
        $this->assertEquals($exp, $res);
    }

    /** 
     * Construct a Card object with arguments and check values.
     */
    public function testCreateCardCheckValues(): void
    {
        $card = new Card();
        $card->setName("test");
        $res = $card->getName();
        $exp = "test";
        $this->assertEquals($exp, $res);
    }
}