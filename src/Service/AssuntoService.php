<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Assunto;

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
}