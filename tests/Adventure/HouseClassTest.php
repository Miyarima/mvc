<?php

namespace App\Adventure;

use App\Adventure\HouseClass;
use App\Entity\House;
use App\Repository\HouseRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class HouseClassTest extends TestCase
{
    private HouseClass $houseClass;
    private ManagerRegistry $doctrine;
    private HouseRepository $repository;

    /** 
     * Constructing a mock of the HouseClass class to be used in the tests.
     */
    protected function setUp(): void
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->repository = $this->createMock(HouseRepository::class);

        $this->houseClass = new HouseClass($this->doctrine, $this->repository);
    }

    /** 
     * Testing if a new entry gets added.  
    */
    public function testCreateGetHouseEntries(): void
    {
        $inv = new House();
        $inv->setName('claymore');
        $inv->setType('sword');
        $inv->setContent('made by g');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$inv]);

        $expectedResult = [["While looking through your little hut, you find theses items"], ["claymore"], ["As you continue exploring, you notice a door to the north"]];
        $actualResult = $this->houseClass->getHouseEntries();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if a new entry gets added.  
    */
    public function testCreateGetHousePickups(): void
    {
        $inv = new House();
        $inv->setName('quest');
        $inv->setType('quest');
        $inv->setContent('kill 5 enemies');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$inv]);

        $expectedResult = [['quest', 'quest', 'kill 5 enemies']];
        $actualResult = $this->houseClass->getHousePickups();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if the correct entry gets removed. 
    */
    public function testCreateRemoveHouseEntry(): void
    {
        $inv = new House();
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
        
        $this->houseClass->removeHouseEntry('look');
    }

    /** 
     * Testing if the correct entry gets updated. 
    */
    public function testCreateUpdateHouseEntry(): void
    {
        $inv = new House();
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

        $this->houseClass->updateHouseEntry(['look', 'info', 'go east']);
    }

        /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessage(): void
    {
        $msg1 = new House();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new House();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new House();
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

        $actualResult = $this->houseClass->getMessage();

        $this->assertEquals('this is the first message', $actualResult);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessageWhereCounterIsOne(): void
    {
        $msg1 = new House();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new House();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new House();
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

        $actualResult = $this->houseClass->getMessage();

        $this->assertEquals('this is a repeated message', $actualResult);
    }
}