<?php

namespace App\Controller;

use App\Repository\RelatorioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/relatorio')]
class RelatorioController extends AbstractController
{
    public function __construct(private readonly RelatorioRepository $relatorioRepository)
    {
    }

    #[Route('', name: 'app_relatorio_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('relatorio/index.html.twig', [
            'grupos' => $this->relatorioRepository->livrosAgrupadosPorAutor(),
        ]);
    }

    #[Route('/pdf', name: 'app_relatorio_pdf', methods: ['GET'])]
    public function pdf(): Response
    {
        $html = $this->renderView('relatorio/pdf.html.twig', [
            'grupos' => $this->relatorioRepository->livrosAgrupadosPorAutor(),
        ]);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio-livros-por-autor.pdf"',
        ]);
    }
}