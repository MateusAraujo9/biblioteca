<?php

namespace App\Entity;

use App\Repository\LivroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivroRepository::class)]
#[ORM\Table(name: 'Livro')]
class Livro
{    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Codl')]
    private ?int $Codl = null;

    #[ORM\Column(name: 'Titulo', length: 40)]
    #[Assert\NotBlank(message: 'Informe o título do livro')]
    #[Assert\Length(max: 40, maxMessage: 'O título deve ter no máximo 40 caracteres')]
    private ?string $Titulo = null;

    #[ORM\Column(name: 'Editora', length: 40)]
    #[Assert\NotBlank(message: 'Informe a editora do livro')]
    private ?string $Editora = null;

    #[ORM\Column(name: 'Edicao')]
    #[Assert\NotBlank(message: 'Informe a edição do livro')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'A edição deve ser maior que zero')]
    private ?int $Edicao = null;

    #[ORM\Column(name: 'AnoPublicacao', length: 4)]
    #[Assert\NotBlank(message: 'Informe o ano de publicação do livro')]
    #[Assert\Regex('/^\d{4}$/', message: 'O ano deve ter 4 dígitos.')]
    private ?string $AnoPublicacao = null;

    #[ORM\Column(name: 'Valor', type: Types::DECIMAL, precision: 6, scale: 2)]
    #[Assert\NotBlank(message: 'Informe o valor do livro')]
    #[Assert\LessThan(value: 9999.99, message: 'O valor deve ser menor que 9.999,99')]
    private ?string $Valor = null;

    /**
     * @var Collection<int, Autor>
     */
    #[ORM\ManyToMany(targetEntity: Autor::class)]
    #[ORM\JoinTable(name: 'Livro_Autor')]
    #[ORM\JoinColumn(name: 'Livro_Codl', referencedColumnName: 'Codl')]
    #[ORM\InverseJoinColumn(name: 'Autor_CodAu', referencedColumnName: 'CodAu')]
    #[Assert\Count(min: 1, minMessage: 'Selecione pelo menos um autor')]
    private Collection $autores;

    /**
     * @var Collection<int, Assunto>
     */
    #[ORM\ManyToMany(targetEntity: Assunto::class)]
    #[ORM\JoinTable(name: 'Livro_Assunto')]
    #[ORM\JoinColumn(name: 'Livro_Codl', referencedColumnName: 'Codl')]
    #[ORM\InverseJoinColumn(name: 'Assunto_CodAs', referencedColumnName: 'CodAs')]
    #[Assert\Count(min: 1, minMessage: 'Selecione pelo menos um assunto')]
    private Collection $assuntos;

    public function __construct()
    {
        $this->autores = new ArrayCollection();
        $this->assuntos = new ArrayCollection();
    }

    public function getCodl(): ?int
    {
        return $this->Codl;
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setTitulo(string $Titulo): static
    {
        $this->Titulo = $Titulo;

        return $this;
    }

    public function getEditora(): ?string
    {
        return $this->Editora;
    }

    public function setEditora(string $Editora): static
    {
        $this->Editora = $Editora;

        return $this;
    }

    public function getEdicao(): ?int
    {
        return $this->Edicao;
    }

    public function setEdicao(int $Edicao): static
    {
        $this->Edicao = $Edicao;

        return $this;
    }

    public function getAnoPublicacao(): ?string
    {
        return $this->AnoPublicacao;
    }

    public function setAnoPublicacao(string $AnoPublicacao): static
    {
        $this->AnoPublicacao = $AnoPublicacao;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->Valor;
    }

    public function setValor(string $Valor): static
    {
        $this->Valor = $Valor;

        return $this;
    }

    /**
     * @return Collection<int, Autor>
     */
    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function addAutor(Autor $autor): static
    {
        if (!$this->autores->contains($autor)) {
            $this->autores->add($autor);
        }

        return $this;
    }

    public function removeAutor(Autor $autor): static
    {
        $this->autores->removeElement($autor);

        return $this;
    }

    /**
     * @return Collection<int, Assunto>
     */
    public function getAssuntos(): Collection
    {
        return $this->assuntos;
    }

    public function addAssunto(Assunto $assunto): static
    {
        if (!$this->assuntos->contains($assunto)) {
            $this->assuntos->add($assunto);
        }

        return $this;
    }

    public function removeAssunto(Assunto $assunto): static
    {
        $this->assuntos->removeElement($assunto);

        return $this;
    }
}
