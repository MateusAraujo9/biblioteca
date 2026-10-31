<?php

namespace App\Entity;

use App\Repository\AssuntoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssuntoRepository::class)]
#[ORM\Table(name: 'Assunto')]
class Assunto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'CodAs')]
    private ?int $CodAs = null;

    #[ORM\Column(name: 'Descricao', length: 20)]
    #[Assert\NotBlank(message: 'Informe a descrição do assunto')]
    #[Assert\Length(max: 20, maxMessage: 'A descrição deve ter no máximo 20 caracteres')]
    private ?string $Descricao = null;

    public function getCodAs(): ?int
    {
        return $this->CodAs;
    }

    public function getDescricao(): ?string
    {
        return $this->Descricao;
    }

    public function setDescricao(string $Descricao): static
    {
        $this->Descricao = $Descricao;

        return $this;
    }
}
