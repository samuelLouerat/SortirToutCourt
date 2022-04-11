<?php

namespace App\Tests;

use App\Entity\Participant;
use PHPUnit\Framework\TestCase;

class ParticipantTest extends TestCase
{
    public function testParticipant(): void
    {
        $participant = (new Participant())
            ->setNom('nom')
            ->setPrenom('prenom')
            ->setTelephone('0612345678')
            ->setMail('mail@mail.com')
            ->setMotPasse('motdepasse')
            ->setAdministrateur(false)
            ->setActif(true);

        $this->assertEquals("nom", $participant->getNom());
        $this->assertEquals("prenom", $participant->getPrenom());
        $this->assertEquals("0612345678", $participant->getTelephone());
        $this->assertEquals("mail@mail.com", $participant->getMail());
        $this->assertFalse($participant->getAdministrateur());
        $this->assertTrue($participant->getActif());
    }
}
