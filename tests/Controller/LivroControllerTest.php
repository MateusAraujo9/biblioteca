<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Suport\CriaEntidadesTrait;
use PHPUnit\Framework\Attributes\DataProvider;

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
            'livro[edicao]' => 1,
            'livro[anoPublicacao]' => '2023',
            'livro[valor]' => 49.90,
            'livro[autores]' => [$autor->getCodAu()],
            'livro[assuntos]' => [$assunto->getCodAs()],
        ]);

        self::assertResponseRedirects('/livro');
        $client->followRedirect();
        self::assertSelectorTextContains('.alert-success', 'Livro cadastrado');
        self::assertSelectorTextContains('table', 'Livro Teste');
        self::assertSelectorTextContains('table', 'Autor Teste');
        self::assertSelectorTextContains('table', 'Assunto Teste');
    }

    #[DataProvider('livrosInvalidos')]
    public function testNaoCadastrarLivroInvalido(array $campos, string $mensagem): void
    {
        $client = static::createClient();
        $dados = $this->dadosValidos();
        $client->request('GET', '/livro/novo');

        $client->submitForm('Salvar', array_merge($dados, $campos));

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('form', $mensagem);
    }

    public static function livrosInvalidos(): iterable
    {
        yield 'titulo vazio' => [['livro[titulo]' => ''], 'Informe o título'];
        yield 'titulo longo' => [['livro[titulo]' => str_repeat('a', 41)], 'O título deve ter no máximo 40 caracteres'];
        yield 'ano com letras' => [['livro[anoPublicacao]' => '20c4'], 'O ano deve ter 4 dígitos.'];
        yield 'edição vazia' => [['livro[edicao]' => ''], 'Informe a edição'];
        yield 'edição zero' => [['livro[edicao]' => 0], 'A edição deve ser maior que zero'];
        yield 'valor vazio' => [['livro[valor]' => ''], 'Informe o valor'];
        yield 'valor acima' => [['livro[valor]' => 10000], 'O valor deve ser menor que 9.999,99'];
        yield 'sem autor' => [['livro[autores]' => []], 'Selecione pelo menos um autor'];
        yield 'sem assunto' => [['livro[assuntos]' => []], 'Selecione pelo menos um assunto'];
    }

    private function dadosValidos(): array
    {
        $autor = $this->criarAutor('Autor Teste');
        $assunto = $this->criarAssunto('Assunto Teste');

        return [
            'livro[titulo]' => 'Livro Teste',
            'livro[editora]' => 'Editora Teste',
            'livro[edicao]' => 1,
            'livro[anoPublicacao]' => '2023',
            'livro[valor]' => 49.90,
            'livro[autores]' => [$autor->getCodAu()],
            'livro[assuntos]' => [$assunto->getCodAs()],
        ];
    }
}