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

    public function testNaoCadastrarAssuntoComDescricaoVazia(): void
    {
        $client = static::createClient();
        $client->request('GET', '/assunto/novo');

        $client->submitForm('Salvar', [
            'assunto[descricao]' => '',
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('form', 'Informe a descrição do assunto');
    }

    public function testNaoCadastrarAssuntoComDescricaoMuitoLonga(): void
    {
        $client = static::createClient();
        $client->request('GET', '/assunto/novo');

        $client->submitForm('Salvar', [
            'assunto[descricao]' => str_repeat('a', 21),
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('form', 'no máximo 20 caracteres');
    }
}