<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Suport\CriaEntidadesTrait;

class LivroControllerTest extends WebTestCase
{
    use CriaEntidadesTrait;

    public function testListagemDeLivrosExibePagina(): void
    {
        $client = static::createClient();
        $this->criarLivro('Livro Teste');

        $client->request('GET', '/livro');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_livro_index');
        self::assertSelectorTextContains('h1', 'Livros');
        self::assertSelectorTextContains('table', 'Livro Teste');
    }

    public function testCadastraLivro(): void
    {
        $client = static::createClient();
        $autor = $this->criarAutor('Autor Teste');
        $assunto = $this->criarAssunto('Assunto Teste');

        $client->request('GET', '/livro/novo');
        $client->submitForm('Salvar', [
            'livro[titulo]' => 'Livro Teste',
            'livro[editora]' => 'Editora Teste',
            'livro[edicao]' => '1ª Edição',
            'livro[anoPublicacao]' => '2023',
            'livro[valor]' => '49.90',
            'livro[autores][]' => $autor->getCodAu(),
            'livro[assuntos][]' => $assunto->getCodAs(),
        ]);

        self::assertResponseRedirects('/livro');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'Livro cadastrado');
        self::assertSelectorTextContains('table', 'Livro Teste');
        self::assertSelectorTextContains('table', 'Autor Teste');
        self::assertSelectorTextContains('table', 'Assunto Teste');
    }
}