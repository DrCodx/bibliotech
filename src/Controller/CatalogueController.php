<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_catalogue', methods: ['GET'])]
    public function index(
        Request $request,
        LivreRepository $livreRepository
    ): Response {
        // Paramètres du formulaire
        $genre = $request->query->get('genre', '');
        $auteur = $request->query->get('auteur', '');
        $tri = $request->query->get('tri', '');

        $livres = $livreRepository->findCatalogue(
            $genre,
            $auteur,
            $tri
        );

        return $this->render('catalogue/index.html.twig', [
            'livres' => $livres,
            'genres' => $livreRepository->findGenres(),
            'auteurs' => $livreRepository->findAuteurs(),
            'filtres' => [
                'genre' => $genre,
                'auteur' => $auteur,
                'tri' => $tri,
            ],
        ]);
    }


    #[Route(
        '/livre/{id}',
        name: 'app_livre',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    public function show(
        Livre $livre,
        LivreRepository $livreRepository,
        EmpruntRepository $empruntRepository
    ): Response {
        return $this->render('catalogue/livre.html.twig', [
            'livre' => $livre,
            'derniersEmprunts' => $empruntRepository
                ->findDerniersPourLivre($livre),
            'livresMemeGenre' => $livreRepository
                ->findMemeGenre($livre),
        ]);
    }


    #[Route('/populaires', name: 'app_populaires', methods: ['GET'])]
    public function populaires(LivreRepository $livreRepository): Response
    {
        return $this->render('catalogue/populaires.html.twig', [
            'livres' => $livreRepository->findPlusEmpruntes(5),
        ]);
    }


}