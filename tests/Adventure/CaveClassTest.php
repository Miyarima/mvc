<?php

namespace App\Adventure;

use App\Adventure\CaveClass;
use App\Entity\Cave;
use App\Repository\CaveRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class CaveClassTest extends TestCase
{
    private CaveClass $caveClass;
    private ManagerRegistry $doctrine;
    private CaveRepository $repository;

    /** 
     * Constructing a mock of the CaveClass class to be used in the tests.
     */
    protected function setUp(): void
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->repository = $this->createMock(CaveRepository::class);

        $this->caveClass = new CaveClass($this->doctrine, $this->repository);
    }

    /** 
     * Testing if a new entry gets added.  
    */
    public function testCreateGetCaveEntries(): void
    {
        $cave = new Cave();
        $cave->setType('info');
        $cave->setContent('looking to the east');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$cave]);

        $expectedResult = [['looking to the east'], ['To return to the path, it also says to go east.']];
        $actualResult = $this->caveClass->getCaveEntries();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if the correct entry gets removed. 
    */
    public function testCreateRemoveCaveEntry(): void
    {
        $cave = new Cave();
        $cave->setName('look');
        $cave->setType('info');
        $cave->setContent('you can go east');
        
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'look'])
            ->willReturn($cave);
        
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($cave, true);
        
        $this->caveClass->removeCaveEntry('look');
    }

    /** 
     * Testing if the correct entry gets updated. 
    */
    public function testCreateUpdateCaveEntry(): void
    {
        $cave = new Cave();
        $cave->setName('look');
        $cave->setType('info');
        $cave->setContent('go west');

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'look'])
            ->willReturn($cave);

        $updatedcave = clone $cave;
        $updatedcave->setContent('go east');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($updatedcave, true);

        $this->caveClass->updateCaveEntry(['look', 'info', 'go east']);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessage(): void
    {
        $msg1 = new Cave();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new Cave();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new Cave();
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

        $actualResult = $this->caveClass->getMessage();

        $this->assertEquals('this is the first message', $actualResult);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessageWhereCounterIsOne(): void
    {
        $msg1 = new Cave();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new Cave();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new Cave();
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

        $actualResult = $this->caveClass->getMessage();

        $this->assertEquals('this is a repeated message', $actualResult);
    }
}