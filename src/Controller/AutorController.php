<?php

namespace App\Controller;

use App\Repository\AutorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/autor')]
class AutorController extends AbstractController
{
    #[Route('', name: 'app_autor_index', methods: ['GET'])]    
    public function index(AutorRepository $autorRepository): Response
    {
        return $this->render('autor/index.html.twig', [
            'autores' => $autorRepository->findAll(),
        ]);
    }
}