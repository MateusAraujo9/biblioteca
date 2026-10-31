<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssuntoControllerTest extends WebTestCase
{
    public function testListagemDeAssuntosExibePagina(): void
    {
        $client = static::createClient();
        $client->request('GET', '/assunto');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Assuntos');
    }

    public function testCadastraAssunto(): void
    {
        $client = static::createClient();
        $client->request('GET', '/assunto/novo');

        $client->submitForm('Salvar', [
            'assunto[descricao]' => 'Ficção',
        ]);

        self::assertResponseRedirects('/assunto');
        $client->followRedirect();
        self::assertSelectorTextContains('table', 'Ficção');
        self::assertSelectorTextContains('.alert-success', 'Assunto cadastrado');
    }
}