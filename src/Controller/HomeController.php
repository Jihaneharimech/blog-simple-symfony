<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render(
            'home/index.html.twig',
            [
                'articles' => $articles,
            ]
        );
    }

    #[Route('/create', name: 'article_create')]
    public function create(Request $request, ArticleRepository $articleRepository)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lier l'utilisateur actuellement connecté comme createur de l'article
            $article->setUser($this->getUser());

            // Définir la date de publication comme la date actuelle
            $article->setDatePublication(new \DateTime());

            $articleRepository->save($article, true);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
