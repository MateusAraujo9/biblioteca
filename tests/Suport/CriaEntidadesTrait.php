<?php

namespace App\Tests\Suport;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Autor;
use App\Entity\Assunto;
use App\Entity\Livro;

trait CriaEntidadesTrait
{
    private function entityManager(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }

    private function criarAutor(string $name = "Machado de Assis"): Autor
    {
        $autor = (new Autor())->setNome($name);
        $this->salvar($autor);
        
        return $autor;
    }

    private function criarAssunto(string $descricao = 'Romance'): Assunto
    {
        $assunto = (new Assunto())->setDescricao($descricao);
        $this->salvar($assunto);

        return $assunto;
    }

    private function criarLivro(string $titulo, ?Autor $autor = null, ?Assunto $assunto = null): \App\Entity\Livro
    {
        $livro = (new Livro())
            ->setTitulo($titulo)
            ->setEditora('Editora Teste')
            ->setEdicao(1)
            ->setAnoPublicacao('2026')
            ->setValor(50.00)
            ->addAutor($autor ?? $this->criarAutor())
            ->addAssunto($assunto ?? $this->criarAssunto());
        $this->salvar($livro);

        return $livro;
    }

    private function salvar(object $entity): void
    {
        $this->entityManager()->persist($entity);
        $this->entityManager()->flush();
    }
}