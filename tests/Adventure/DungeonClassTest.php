<?php

namespace App\Adventure;

use App\Adventure\DungeonClass;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class DungeonClassTest extends TestCase
{
    private DungeonClass $dungeonClass;
    private ManagerRegistry $doctrine;
    private DungeonRepository $repository;

    /** 
     * Constructing a mock of the DungeonClass class to be used in the tests.
     */
    protected function setUp(): void
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->repository = $this->createMock(DungeonRepository::class);

        $this->dungeonClass = new DungeonClass($this->doctrine, $this->repository);
    }

    /** 
     * Testing if a new entry gets added. 
    */
    public function testCreateGetDungeonEntries(): void
    {
        $dungeon = new Dungeon();
        $dungeon->setType('info');
        $dungeon->setContent('looking to the east');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$dungeon]);

        $expectedResult = [['looking to the east']];
        $actualResult = $this->dungeonClass->getDungeonEntries();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if the correct entry gets returned. 
    */
    public function testCreateGetDungeonBoss(): void
    {
        $dungeon = new Dungeon();
        $dungeon->setName('Demon');
        $dungeon->setType('boss');
        $dungeon->setContent('1');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$dungeon]);

        $expectedResult = [['Demon', '1']];
        $actualResult = $this->dungeonClass->getDungeonBoss();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if the correct entry gets removed. 
    */
    public function testCreateRemoveDungeonEntry(): void
    {
        $dungeon = new Dungeon();
        $dungeon->setName('Demon');
        $dungeon->setType('boss');
        $dungeon->setContent('1');
        
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Demon'])
            ->willReturn($dungeon);
        
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($dungeon, true);
        
        $this->dungeonClass->removeDungeonEntry('Demon');
    }
    
    /** 
     * Testing if the correct entry gets updated. 
    */
    public function testCreateUpdateDungeonEntry(): void
    {
        $dungeon = new Dungeon();
        $dungeon->setName('Demon');
        $dungeon->setType('boss');
        $dungeon->setContent('1');

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'Demon'])
            ->willReturn($dungeon);

        $updatedDungeon = clone $dungeon;
        $updatedDungeon->setContent('0');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($updatedDungeon, true);

        $this->dungeonClass->updateDungeonEntry(['Demon', 'boss', '0']);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessage(): void
    {
        $msg1 = new Dungeon();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new Dungeon();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new Dungeon();
        $visit->setName('visit');
        $visit->setType('counter');
        $visit->setContent('0');

        $messages = [$msg1, $msg2];
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['type' => 'message'])
            ->willReturn($messages);

        $this->repository->expects($this->exactly(2))
            ->method('findOneBy')
            ->with(['name' => 'visit'])
            ->willReturn($visit);

        $actualResult = $this->dungeonClass->getMessage();

        $this->assertEquals('this is the first message', $actualResult);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessageWhereCounterIsOne(): void
    {
        $msg1 = new Dungeon();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new Dungeon();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new Dungeon();
        $visit->setName('visit');
        $visit->setType('counter');
        $visit->setContent('1');

        $messages = [$msg1, $msg2];
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['type' => 'message'])
            ->willReturn($messages);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'visit'])
            ->willReturn($visit);

        $actualResult = $this->dungeonClass->getMessage();

        $this->assertEquals('this is a repeated message', $actualResult);
    }
}