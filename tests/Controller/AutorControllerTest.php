<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Autor;
use Doctrine\ORM\EntityManagerInterface;

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

        $client->request('POST', '/autor/' . $autor->getCodAu() . '/excluir');

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
}