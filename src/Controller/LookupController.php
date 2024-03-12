<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LookupController extends AbstractController //controller qui gère la recherche
{
    /**
     * @Route("/lookup", name="app_lookup")
     */
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $search = $request->query->get('search'); //récuperer la recherche via le query qui correspond lui aux paramètres qui sont dans l'url
        $articles = $articleRepository->findBySearch($search);//récupère tous les articles de la BDD  pour pouvoir faire une recherche sur chaque champ.
        return $this->render('lookup/index.html.twig', [
            'controller_name' => 'LookupController',
            'articles' => $articles
        ]);
    }
}
