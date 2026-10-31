<?php

namespace App\Controller;

use App\Entity\Livro;
use App\Repository\LivroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livro')]
class LivroController extends AbstractController
{
    #[Route('', name: 'app_livro_index', methods: ['GET'])]
    public function index(LivroRepository $livroRepository): Response
    {
        $livros = $livroRepository->findAllComRelacionamentos();

        return $this->render('livro/index.html.twig', [
            'livros' => $livros,
        ]);
    }
}