<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AutorControllerTest extends WebTestCase
{
    public function testListagemDeAutoresExibePagina(): void
    {
        $client = static::createClient();
        $client->request('GET', '/autor');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Autores');
    }
}