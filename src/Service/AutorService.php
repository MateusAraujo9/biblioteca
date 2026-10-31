<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Autor;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use App\Exception\AutorVinculadoException;

class AutorService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function salvar(Autor $autor): void
    {
        $this->entityManager->persist($autor);
        $this->entityManager->flush();
    }

    public function excluir(Autor $autor): void
    {
        try {
            $this->entityManager->remove($autor);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new AutorVinculadoException();
        }
    }
}