<?php

namespace App\Tests\Repository;

use App\Repository\RelatorioRepository;
use App\Tests\Suport\CriaEntidadesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RelatorioRepositoryTest extends KernelTestCase
{
    use CriaEntidadesTrait;

    protected function setUp(): void
    {
        self::bootKernel();
    }

    private function repo(): RelatorioRepository
    {
        return new RelatorioRepository($this->entityManager()->getConnection());
    }

    private function linhasDosLivros(int ...$codls): array
    {
        return array_values(array_filter(
            $this->repo()->livrosPorAutor(),
            fn (array $linha) => in_array((int) $linha['Codl'], $codls, true)
        ));
    }

    public function testLivroComDoisAutoresApareceUmaVezParaCadaAutor(): void
    {
        $livro = $this->criarLivro('Livro X', $this->criarAutor('Ana'));
        $livro->addAutor($this->criarAutor('Bruno'));
        $this->salvar($livro);

        $linhas = $this->linhasDosLivros($livro->getCodl());

        self::assertCount(2, $linhas);
        self::assertSame(['Ana', 'Bruno'], array_column($linhas, 'autor'));
        self::assertSame('Ana, Bruno', $linhas[0]['autores']);
    }
}