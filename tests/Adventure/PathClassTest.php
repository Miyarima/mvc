<?php

namespace App\Adventure;

use App\Adventure\PathClass;
use App\Entity\Path;
use App\Repository\PathRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class PathClassTest extends TestCase
{
    private PathClass $pathClass;
    private ManagerRegistry $doctrine;
    private PathRepository $repository;

    /** 
     * Constructing a mock of the pathClass class to be used in the tests.
     */
    protected function setUp(): void
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->repository = $this->createMock(PathRepository::class);

        $this->pathClass = new PathClass($this->doctrine, $this->repository);
    }

    /** 
     * Testing if a new entry gets added.  
    */
    public function testCreateGetPathEntries(): void
    {
        $path = new Path();
        $path->setType('info');
        $path->setContent('looking to the east');

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$path]);

        $expectedResult = [['looking to the east']];
        $actualResult = $this->pathClass->getPathEntries();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /** 
     * Testing if the correct entry gets removed. 
    */
    public function testCreateRemovePathEntry(): void
    {
        $cave = new Path();
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
        
        $this->pathClass->removePathEntry('look');
    }

    /** 
     * Testing if the correct entry gets updated. 
    */
    public function testCreateUpdatePathEntry(): void
    {
        $path = new Path();
        $path->setName('look');
        $path->setType('info');
        $path->setContent('go west');

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'look'])
            ->willReturn($path);

        $updatedcave = clone $path;
        $updatedcave->setContent('go east');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($updatedcave, true);

        $this->pathClass->updatePathEntry(['look', 'info', 'go east']);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessage(): void
    {
        $msg1 = new Path();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new Path();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new Path();
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

        $actualResult = $this->pathClass->getMessage();

        $this->assertEquals('this is the first message', $actualResult);
    }

    /** 
     * Testing if the correct messages gets returned. 
    */
    public function testCreateGetMessageWhereCounterIsOne(): void
    {
        $msg1 = new Path();
        $msg1->setName('first message');
        $msg1->setType('message');
        $msg1->setContent('this is the first message');

        $msg2 = new Path();
        $msg2->setName('repeated message');
        $msg2->setType('message');
        $msg2->setContent('this is a repeated message');

        $visit = new Path();
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

        $actualResult = $this->pathClass->getMessage();

        $this->assertEquals('this is a repeated message', $actualResult);
    }
}