<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8888/');



        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bucket List');

    }
}
