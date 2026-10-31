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
use App\Exception\AssuntoVinculadoException;

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

    #[Route('/{id}/editar', name: 'app_assunto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assunto $assunto): Response
    {
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->assuntoService->salvar($assunto);
            $this->addFlash('success', 'Assunto atualizado');

            return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assunto/edit.html.twig', [
            'form' => $form,
            'assunto' => $assunto,
        ]);
    }

    #[Route('/{id}/excluir', name: 'app_assunto_delete', methods: ['POST'])]
    public function delete(Request $request, Assunto $assunto): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$assunto->getCodAs(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF inválido');
            return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
        }

        try {
            $this->assuntoService->excluir($assunto);
            $this->addFlash('success', 'Assunto excluído');
        } catch (AssuntoVinculadoException $e) {
            $this->addFlash('danger', 'O assunto está vinculado a um livro');
        }

        return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
    }
}