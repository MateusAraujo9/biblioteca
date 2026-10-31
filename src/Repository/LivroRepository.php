<?php

namespace App\Repository;

use App\Entity\Livro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livro>
 */
class LivroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livro::class);
    }

    public function findAllComRelacionamentos(): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.autores', 'a')
            ->addSelect('a')
            ->leftJoin('l.assuntos', 's')
            ->addSelect('s')
            ->getQuery()
            ->getResult();
    }
}
