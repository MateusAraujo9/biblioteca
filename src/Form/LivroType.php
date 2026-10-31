<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Autor;
use App\Repository\AutorRepository;
use App\Entity\Assunto;
use App\Repository\AssuntoRepository;
use App\Entity\Livro;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'empty_data' => '',
                'attr' => [
                    'maxlength' => 40,
                ],
            ])
            ->add('editora', TextType::class, [
                'label' => 'Editora',
                'empty_data' => '',
                'attr' => [
                    'maxlength' => 40,
                ],
            ])
            ->add('edicao', IntegerType::class, [
                'label' => 'Edição',
                'empty_data' => '',
                'attr' => [
                    'min' => 1,
                ],
            ])
            ->add('anoPublicacao', TextType::class, [
                'label' => 'Ano de Publicação',
                'empty_data' => '',
                'attr' => [
                    'maxlength' => 4,
                    'inputmode' => 'numeric',
                ],
            ])
            ->add('valor', MoneyType::class, [
                'label' => 'Valor',
                'currency' => 'BRL',
                'input' => 'string',
            ])
            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'nome',
                'multiple' => true,
                'query_builder' => fn (AutorRepository $ar) => $ar->createQueryBuilder('a')->orderBy('a.Nome', 'ASC'),
            ])
            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'descricao',
                'multiple' => true,
                'query_builder' => fn (AssuntoRepository $ar) => $ar->createQueryBuilder('a')->orderBy('a.Descricao', 'ASC'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
        ]);
    }
}