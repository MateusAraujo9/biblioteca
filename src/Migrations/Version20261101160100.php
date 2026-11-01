<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261101160100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
        CREATE VIEW vw_livros_por_autor AS
        SELECT a.CodAu,
               a.Nome AS autor,
               l.Codl, l.Titulo, l.Editora, l.Edicao, l.AnoPublicacao, l.Valor,
               GROUP_CONCAT(DISTINCT a2.Nome ORDER BY a2.Nome SEPARATOR ', ') AS autores,
               GROUP_CONCAT(DISTINCT s.Descricao ORDER BY s.Descricao SEPARATOR ', ') AS assuntos
        FROM Autor a
        JOIN Livro_Autor la        ON la.Autor_CodAu = a.CodAu
        JOIN Livro l               ON l.Codl = la.Livro_Codl
        JOIN Livro_Autor la2       ON la2.Livro_Codl = l.Codl
        JOIN Autor a2              ON a2.CodAu = la2.Autor_CodAu
        LEFT JOIN Livro_Assunto ls ON ls.Livro_Codl = l.Codl
        LEFT JOIN Assunto s        ON s.CodAs = ls.Assunto_CodAs
        GROUP BY a.CodAu, a.Nome, l.Codl, l.Titulo, l.Editora, l.Edicao, l.AnoPublicacao, l.Valor
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS vw_livros_por_autor');

    }
}
