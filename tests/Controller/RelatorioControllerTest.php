<?php

namespace App\Tests\Controller;

use App\Tests\Suport\CriaEntidadesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RelatorioControllerTest extends WebTestCase
{
    use CriaEntidadesTrait;

    public function testRelatorioAgrupaLivrosPorAutor(): void
    {
        $client = static::createClient();
        $livro = $this->criarLivro('Livro X', $this->criarAutor('Ana'));
        $livro->addAutor($this->criarAutor('Bruno'));
        $this->salvar($livro);

        $client->request('GET', '/relatorio');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_relatorio_index');
        self::assertSelectorTextContains('h1', 'Relatório');
        self::assertAnySelectorTextContains('h2', 'Ana');
        self::assertAnySelectorTextContains('h2', 'Bruno');
        self::assertSame(2, substr_count($client->getResponse()->getContent(), 'Livro X'));
    }

    public function testRelatorioEmPdf(): void
    {
        $client = static::createClient();
        $this->criarLivro('Livro X');

        $client->request('GET', '/relatorio/pdf');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('app_relatorio_pdf');
        self::assertResponseHeaderSame('Content-Type', 'application/pdf');
        self::assertStringStartsWith('%PDF', $client->getResponse()->getContent());
    }
}