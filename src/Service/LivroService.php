<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livro;

class LivroService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function salvar(Livro $livro): void
    {
        $this->entityManager->persist($livro);
        $this->entityManager->flush();
    }

    public function excluir(Livro $livro): void
    {
        $this->entityManager->remove($livro);
        $this->entityManager->flush();
    }
}