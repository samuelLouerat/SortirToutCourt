<?php

namespace App\Tests;

use App\Entity\Campus;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser(): void
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
            ->setCampus($campus)
        ;
        $this->assertEquals('testunit@gmail.com', $user->getEmail());
        $this->assertEquals('testunit@gmail.com', $user->getUserIdentifier());
        $this->assertEquals('Lastname', $user->getLastName());
        $this->assertEquals('Firstname', $user->getFirstName());
        $this->assertEquals('0612345678', $user->getPhone());
        $this->assertNotEquals(true, $user->getAdmin());
        $this->assertEquals(true, $user->getActive());
        $this->assertEquals('Nantes', $user->getCampus()->getName());
        $this->assertNotNull($user->getRoles());
    }
}
