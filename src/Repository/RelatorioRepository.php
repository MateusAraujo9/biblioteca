<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class RelatorioRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function livrosPorAutor(): array
    {
        return $this->connection->fetchAllAssociative(
            'SELECT * FROM vw_livros_por_autor ORDER BY autor, CodAu, Titulo'
        );
    }
}