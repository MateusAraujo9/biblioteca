<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaginaErroTest extends WebTestCase
{
    public function testPaginaErro404(): void
    {
        $client = static::createClient(['debug' => false]);
        $client->request('GET', '/pagina-inexistente');

        self::assertResponseStatusCodeSame(404);
        self::assertSelectorTextContains('h1', 'Página não encontrada');
    }
}