<?php

namespace App\Controller;

use App\Repository\AssuntoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AssuntoController extends AbstractController
{
    #[Route('/assunto', name: 'app_assunto_index', methods: ['GET'])]
    public function index(AssuntoRepository $assuntoRepository): Response
    {
        return $this->render('assunto/index.html.twig', [
            'assuntos' => $assuntoRepository->findAll(),
        ]);
    }
}