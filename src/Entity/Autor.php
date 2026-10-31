<?php

namespace App\Entity;

use App\Repository\AutorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AutorRepository::class)]
#[ORM\Table(name: 'Autor')]
class Autor
{    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'CodAu')]
    private ?int $CodAu = null;

    #[ORM\Column(name: 'Nome', length: 40)]
    #[Assert\NotBlank(message: 'Informe o nome do autor.')]
    #[Assert\Length(max: 40, maxMessage: 'O nome deve ter no máximo {{ limit }} caracteres.')]
    private ?string $Nome = null;

    public function getCodAu(): ?int
    {
        return $this->CodAu;
    }

    public function getNome(): ?string
    {
        return $this->Nome;
    }

    public function setNome(string $Nome): static
    {
        $this->Nome = $Nome;

        return $this;
    }
}
