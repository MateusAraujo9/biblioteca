<?php

namespace App\Controller;

use App\Repository\AssuntoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\AssuntoService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Assunto;
use App\Form\AssuntoType;

#[Route('/assunto')]
class AssuntoController extends AbstractController
{
    public function __construct(
        private readonly AssuntoService $assuntoService,
    ) {
    }

    #[Route('', name: 'app_assunto_index', methods: ['GET'])]
    public function index(AssuntoRepository $assuntoRepository): Response
    {
        return $this->render('assunto/index.html.twig', [
            'assuntos' => $assuntoRepository->findAll(),
        ]);
    }

    #[Route('/novo', name: 'app_assunto_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $assunto = new Assunto();
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->assuntoService->salvar($assunto);

            $this->addFlash('success', 'Assunto cadastrado');

            return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assunto/new.html.twig', [
            'form' => $form,
        ]);
    }
}