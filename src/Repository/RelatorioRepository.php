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

    public function livrosAgrupadosPorAutor(): array
    {
        $grupos = [];

        foreach ($this->livrosPorAutor() as $linha) {
            $grupos[$linha['CodAu']]['autor'] = $linha['autor'];
            $grupos[$linha['CodAu']]['livros'][] = $linha;
        }

        return $grupos;
    }
}