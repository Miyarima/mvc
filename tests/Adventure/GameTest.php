<?php

namespace App\Adventure;

use PHPUnit\Framework\TestCase;
use App\Repository\PlayerRepository;
use App\Repository\HouseRepository;
use App\Repository\PathRepository;
use App\Repository\CaveRepository;
use App\Repository\DungeonRepository;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionClass;

class GameTest extends TestCase
{
    private Game $game;

    /** 
     * Constructing a proptery of the Game class to be used in the tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $doctrineMock = $this->createMock(ManagerRegistry::class);
        $playerRepositoryMock = $this->createMock(PlayerRepository::class);
        $houseRepositoryMock = $this->createMock(HouseRepository::class);
        $pathRepositoryMock = $this->createMock(PathRepository::class);
        $caveRepositoryMock = $this->createMock(CaveRepository::class);
        $dungeonRepositoryMock = $this->createMock(DungeonRepository::class);

        $this->game = new Game(
            $doctrineMock,
            $playerRepositoryMock,
            $houseRepositoryMock,
            $pathRepositoryMock,
            $caveRepositoryMock,
            $dungeonRepositoryMock
        );
    }

    /** 
     * Testing the properties of the Game class.
     */
    public function testCreateGameProperties(): void
    {
        $reflection = new ReflectionClass(Game::class);
        $inventory = $reflection->getProperty('inventory');
        $house = $reflection->getProperty('house');
        $path = $reflection->getProperty('path');
        $cave = $reflection->getProperty('cave');
        $dungeon = $reflection->getProperty('dungeon');

        $inventory->setAccessible(true);
        $house->setAccessible(true);
        $path->setAccessible(true);
        $cave->setAccessible(true);
        $dungeon->setAccessible(true);

        $this->assertInstanceOf(Inventory::class, $inventory->getValue($this->game));
        $this->assertInstanceOf(HouseClass::class, $house->getValue($this->game));
        $this->assertInstanceOf(PathClass::class, $path->getValue($this->game));
        $this->assertInstanceOf(CaveClass::class, $cave->getValue($this->game));
        $this->assertInstanceOf(DungeonClass::class, $dungeon->getValue($this->game));
    }

    /** 
     * Testing the to fail the commmand parse.
     */
    public function testCreateGameCommandFail(): void
    {
        $result = $this->game->command("hello", "house");

        $this->assertEquals(["error", ["I'm not familiar with your usage of 'hello'"]], $result);
    }

    /** 
     * Testing the to fail the pickup commmand.
     */
    public function testCreateGameCommandPickupFail(): void
    {
        $result = $this->game->command("pickup sword", "cave");

        $this->assertEquals(["pickup", ["To use 'pickup,' you must be inside the house."]], $result);
    }

    /** 
     * Testing the to fail the train commmand.
     */
    public function testCreateGameCommandTrainFail(): void
    {
        $result = $this->game->command("train", "house");

        $this->assertEquals(["train", ["To use the train, you must be inside the caves."]], $result);
    }

    /** 
     * Testing the to fail the kill commmand.
     */
    public function testCreateGameCommandKillFail(): void
    {
        $result = $this->game->command("kill", "house");

        $this->assertEquals(["kill", ["To use the kill, you must be inside the dungeon."]], $result);
    }

    /** 
     * Testing the to fail the look commmand.
     */
    public function testCreateGameCommandLookFail(): void
    {
        $result = $this->game->command("look", "moon");

        $this->assertEquals(["look", "You can't"], $result);
    }

    /** 
     * Testing the to fail the message commmand.
     */
    public function testCreateGameCommandMessageFail(): void
    {
        $result = $this->game->message("moon");

        $this->assertEquals("No messages were found", $result);
    }

    /** 
     * Testing the help command.
     */
    public function testCreateGameCommandHelp(): void
    {
        $result = $this->game->command("help", "");

        $this->assertEquals([
            "help",
            [
            "Theses are the things you can do:",
            "Use the 'go' command to move in different directions e.g south.",
            "Use 'look' to see what's around you.",
            "'Inventory' to see your stats and what's on your person",
            "'Pickup' to pick up items",
            "'Train' to enhance you stats",
            "'Help' to see this message again",
            ]
        ], $result);
    }

    /** 
     * Testing the go command for moving south.
     */
    public function testCreateGameCommandMoveSouth(): void
    {
        $move = $this->game->command("go south", "path");
        $moveFailed = $this->game->command("go south", "house");

        $this->assertEquals(['go', 'house_adventure'], $move);
        $this->assertEquals(["go", "You can't"], $moveFailed);
    }

    /** 
     * Testing the go command for moving north.
     */
    public function testCreateGameCommandMoveNorth(): void
    {
        $move = $this->game->command("go north", "path");
        $moveFailed = $this->game->command("go north", "cave");

        $this->assertEquals(['go', 'dungeon_adventure'], $move);
        $this->assertEquals(["go", "You can't"], $moveFailed);
    }

    /** 
     * Testing the go command for moving west.
     */
    public function testCreateGameCommandMoveWest(): void
    {
        $move = $this->game->command("go west", "path");
        $moveFailed = $this->game->command("go west", "cave");

        $this->assertEquals(['go', 'cave_adventure'], $move);
        $this->assertEquals(["go", "You can't"], $moveFailed);
    }

    /** 
     * Testing the go command for moving east.
     */
    public function testCreateGameCommandMoveEast(): void
    {
        $move = $this->game->command("go east", "cave");
        $moveFailed = $this->game->command("go east", "path");

        $this->assertEquals(['go', 'path_adventure'], $move);
        $this->assertEquals(["go", "You can't"], $moveFailed);
    }

    /** 
     * Testing the go command for moving to mars.
     */
    public function testCreateGameCommandMoveMars(): void
    {
        $move = $this->game->command("go mars", "cave");

        $this->assertEquals(["go", "You can't"], $move);
    }
}
