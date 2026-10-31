<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Autor;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livro;

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

    public function testEditarAutor(): void
    {
        $client = static::createClient();
        $autor = $this->criarAutor('Machado');

        $client->request('GET', '/autor/' . $autor->getCodAu() . '/editar');
        $client->submitForm('Salvar', [
            'autor[nome]' => 'Machado de Assis',
        ]);

        self::assertResponseRedirects('/autor');
        $client->followRedirect();
        self::assertSelectorTextContains('table', 'Machado de Assis');
        self::assertSelectorTextContains('.alert-success', 'Autor atualizado');
    }

    public function testEditarAutorInexistenteRetorna404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/autor/999999/editar');

        self::assertRouteSame('app_autor_edit');
        self::assertResponseStatusCodeSame(404);
    }

    public function testExcluirAutor(): void
    {
        $client = static::createClient();
        $autor = $this->criarAutor('Machado');
        $id = $autor->getCodAu();

        $crawler = $client->request('GET', '/autor');
        $form = $crawler->filter(sprintf('form[action="/autor/%d/excluir"]', $id))->form();
        $client->submit($form);

        self::assertResponseRedirects('/autor');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'Autor excluído');
    }

    private function criarAutor(string $name): Autor
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $autor = (new Autor())->setNome($name);
        $entityManager->persist($autor);
        $entityManager->flush();
        
        return $autor;
    }

    public function testNaoExcluirAutorVinculadoALivro(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Cria um autor e um livro vinculado a ele
        $autor = (new Autor())->setNome('Autor Vinculado');
        $entityManager->persist($autor);
        $livro = (new Livro())
        ->setTitulo('Livro Teste')
        ->setEditora('Editora Teste')
        ->setEdicao(1)
        ->setAnoPublicacao('2024')
        ->setValor(50.00)
        ->addAutor($autor);
        $entityManager->persist($livro);
        $entityManager->flush();

        // Tenta excluir o autor vinculado ao livro
        $crawler = $client->request('GET', '/autor');
        $form = $crawler->filter(sprintf('form[action="/autor/%d/excluir"]', $autor->getCodAu()))->form();
        $client->submit($form);

        self::assertResponseRedirects('/autor');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-danger', 'O autor está vinculado a um livro');
    }
}