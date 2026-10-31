<?php

namespace App\Controller;

use App\Repository\AutorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Autor;
use App\Form\AutorType;

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

    #[Route('/novo', name: 'app_autor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $autor = new \App\Entity\Autor();
        $form = $this->createForm(\App\Form\AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($autor);
            $entityManager->flush();

            $this->addFlash('success', 'Autor cadastrado');

            return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('autor/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editar', name: 'app_autor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Autor $autor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Autor atualizado');

            return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('autor/edit.html.twig', [
            'form' => $form,
            'autor' => $autor,
        ]);
    }
}