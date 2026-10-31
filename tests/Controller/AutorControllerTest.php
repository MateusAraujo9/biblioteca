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

    public function testCadastraAutor(): void
    {
        $client = static::createClient();
        $client->request('GET', '/autor/novo');

        $client->submitForm('Salvar', [
            'autor[nome]' => 'Machado de Assis',
        ]);

        self::assertResponseRedirects('/autor');
        $client->followRedirect();
        self::assertSelectorTextContains('table', 'Machado de Assis');
        self::assertSelectorTextContains('.alert-success', 'Autor cadastrado');
    }

    public function testNaoCadastrarAutorComNomeVazio(): void
    {
        $client = static::createClient();
        $client->request('GET', '/autor/novo');

        $client->submitForm('Salvar', [
            'autor[nome]' => '',
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('form', 'Informe o nome do autor');
    }

    public function testNaoCadastrarAutorComNomeMuitoLongo(): void
    {
        $client = static::createClient();
        $client->request('GET', '/autor/novo');

        $client->submitForm('Salvar', [
            'autor[nome]' => str_repeat('a', 41),
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('form', 'no máximo 40 caracteres');
    }
}