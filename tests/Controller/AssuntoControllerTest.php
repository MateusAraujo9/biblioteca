<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Assunto;
use App\Entity\Livro;
use Doctrine\ORM\EntityManagerInterface;
use App\Tests\Suport\CriaEntidadesTrait;

class AssuntoControllerTest extends WebTestCase
{
    use CriaEntidadesTrait;

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

    public function testEditarAssuntoInexistenteRetorna404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/assunto/999999/editar');

        self::assertRouteSame('app_assunto_edit');
        self::assertResponseStatusCodeSame(404);
    }

    public function testExcluirAssunto(): void
    {
        $client = static::createClient();
        $assunto = $this->criarAssunto('Ficção');
        $id = $assunto->getCodAs();

        $crawler = $client->request('GET', '/assunto');
        $form = $crawler->filter(sprintf('form[action="/assunto/%d/excluir"]', $id))->form();
        $client->submit($form);

        self::assertResponseRedirects('/assunto');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'Assunto excluído');
    }

    public function testNaoExcluirAssuntoVinculadoALivro(): void
    {
        $client = static::createClient();
        $assunto = $this->criarAssunto('Ficção');
        $id = $assunto->getCodAs();

        // Vincula o assunto a um livro
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $livro = new \App\Entity\Livro();
        $livro = (new Livro())
        ->setTitulo('Livro Teste')
        ->setEditora('Editora Teste')
        ->setEdicao(1)
        ->setAnoPublicacao('2024')
        ->setValor(50.00)
        ->addAssunto($assunto);
        $entityManager->persist($livro);
        $entityManager->flush();

        $crawler = $client->request('GET', '/assunto');
        $form = $crawler->filter(sprintf('form[action="/assunto/%d/excluir"]', $id))->form();
        $client->submit($form);

        self::assertResponseRedirects('/assunto');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-danger', 'O assunto está vinculado a um livro');
    }
}