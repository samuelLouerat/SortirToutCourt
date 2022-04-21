<?php

namespace App\Tests;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEvent(): void
    {
        $campus = (new Campus())
            ->setName('Nantes');
        $user = (new User())
            ->setEmail('testunit@gmail.com')
            ->setPassword('password')
            ->setLastName('Lastname')
            ->setFirstName('Firstname')
            ->setPhone('0612345678')
            ->setAdmin(false)
            ->setActive(true)
            ->setPseudo('pseudotestunit')
            ->setCampus($campus);
        $event = (new Event())
        ->setName('On va à la plage')
        ->setStartTime(new \DateTime('2022-04-23T10:00:00'))
        ->setDuration(new \DateInterval('P0DT6H'))
        ->setRegistrationTimeLimit(new \DateTime('2022-04-22T14:00:00'))
        ->setRegistrationMax('4')
        ->setEventInfo('Rdv sur le parking de l\'ENI pour aller à la plage')
        ->setOrganizer($user)
        ;
        $this->assertEquals('On va à la plage', $event->getName());
        $this->assertEquals('2022-04-23T10:00:00', $event->getStartTime());
        $this->assertEquals('P0DT6H', $event->getDuration());
        $this->assertEquals('2022-04-22T14:00:00', $event->getRegistrationTimeLimit());
        $this->assertEquals('4', $event->getRegistrationMax());
        $this->assertEquals('pseudotestunit', $event->getOrganizer()->getPseudo());


    }

}
