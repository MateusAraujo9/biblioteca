<?php

namespace App\Controller;

use App\Entity\Livro;
use App\Repository\LivroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LivroType;
use App\Service\LivroService;

#[Route('/livro')]
class LivroController extends AbstractController
{

    public function __construct(
        private readonly LivroService $livroService,
    ) {
    }

    #[Route('', name: 'app_livro_index', methods: ['GET'])]
    public function index(LivroRepository $livroRepository): Response
    {
        $livros = $livroRepository->findAllComRelacionamentos();

        return $this->render('livro/index.html.twig', [
            'livros' => $livros,
        ]);
    }

    #[Route('/novo', name: 'app_livro_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $livro = new Livro();
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->livroService->salvar($livro);

            $this->addFlash('success', 'Livro cadastrado');

            return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livro/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}/editar', name: 'app_livro_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livro $livro): Response
    {
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->livroService->salvar($livro);
            $this->addFlash('success', 'Livro atualizado');

            return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livro/edit.html.twig', [
            'livro' => $livro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/excluir', name: 'app_livro_delete', methods: ['POST'])]
    public function delete(Request $request, Livro $livro): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$livro->getCodl(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Token CSRF inválido. O livro não foi excluído.');
            return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        }

            $this->livroService->excluir($livro);
            $this->addFlash('success', 'Livro excluído');

        return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
    }
}