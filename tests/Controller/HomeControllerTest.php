<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testPaginaInicialExibeMenu(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Biblioteca');
        self::assertSelectorExists('a[href="/livro"]');
        self::assertSelectorExists('a[href="/autor"]');
        self::assertSelectorExists('a[href="/assunto"]');
        self::assertSelectorExists('a[href="/relatorio"]');
    }
}