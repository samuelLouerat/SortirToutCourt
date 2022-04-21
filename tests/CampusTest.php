<?php

namespace App\Tests;

use App\Entity\Campus;
use PHPUnit\Framework\TestCase;

class CampusTest extends TestCase
{
    public function testCampus(): void
    {
        $campus = (new Campus())
            ->setName('Rennes');
        $this->assertEquals('Rennes', $campus->getName());
//        $this->assertNotNull( $campus->getId());
    }
}
