<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Assunto;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use App\Exception\AssuntoVinculadoException;

class AssuntoService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function salvar(Assunto $assunto): void
    {
        $this->entityManager->persist($assunto);
        $this->entityManager->flush();
    }

    public function excluir(Assunto $assunto): void
    {
        try {
            $this->entityManager->remove($assunto);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new AssuntoVinculadoException();
        }
        
    }
}