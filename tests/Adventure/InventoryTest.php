<?php

namespace App\Adventure;

use App\Adventure\Inventory;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class InventoryTest extends TestCase
{
    private Inventory $inventory;
    private ManagerRegistry $doctrine;
    private PlayerRepository $repository;

    /** 
     * Constructing a mock of the inventory class to be used in the tests.
     */
    protected function setUp(): void
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->repository = $this->createMock(PlayerRepository::class);

        $this->inventory = new Inventory($this->doctrine, $this->repository);
    }

    /** 
     * Testing if a new entry gets added.  
    */
    public function testCreateGetInventoryEntries(): void
    {
        $inv = new Player();
        $inv->setName('info');
        $inv->setContent('looking to the east');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$inv]);

        $expectedResult = [["info", " | ", 'looking to the east']];
        $actualResult = $this->inventory->getInventoryEntries();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if a new entry gets added.  
    */
    public function testCreateGetInventoryStats(): void
    {
        $inv = new Player();
        $inv->setName('attack');
        $inv->setType('stat');
        $inv->setContent('15');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$inv]);

        $expectedResult = [["attack", '15']];
        $actualResult = $this->inventory->getInventoryStats();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if the correct entry gets removed. 
    */
    public function testCreateRemoveInventoryEntry(): void
    {
        $inv = new Player();
        $inv->setName('look');
        $inv->setType('info');
        $inv->setContent('you can go east');
        
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'look'])
            ->willReturn($inv);
        
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($inv, true);
        
        $this->inventory->removeInventoryEntry('look');
    }

    /** 
     * Testing if the correct entry gets updated. 
    */
    public function testCreateUpdateInventoryEntry(): void
    {
        $inv = new Player();
        $inv->setName('look');
        $inv->setType('info');
        $inv->setContent('go west');

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'look'])
            ->willReturn($inv);

        $updatedcave = clone $inv;
        $updatedcave->setContent('go east');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($updatedcave, true);

        $this->inventory->updateInventoryEntry(['look', 'info', 'go east']);
    }
}