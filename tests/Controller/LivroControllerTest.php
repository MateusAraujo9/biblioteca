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
}