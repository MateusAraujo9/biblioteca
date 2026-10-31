<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Assunto;
use Doctrine\ORM\EntityManagerInterface;

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

    public function testEditarAssunto(): void
    {
        $client = static::createClient();
        $assunto = $this->criarAssunto('Ficção');

        $client->request('GET', '/assunto/' . $assunto->getCodAs() . '/editar');
        $client->submitForm('Salvar', [
            'assunto[descricao]' => 'Romance',
        ]);

        self::assertResponseRedirects('/assunto');
        $client->followRedirect();
        self::assertSelectorTextContains('table', 'Romance');
        self::assertSelectorTextContains('.alert-success', 'Assunto atualizado');
    }

    private function criarAssunto(string $descricao): Assunto
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $assunto = (new Assunto())->setDescricao($descricao);
        $entityManager->persist($assunto);
        $entityManager->flush();

        return $assunto;
    }
}