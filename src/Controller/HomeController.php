<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
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
            // Lie l'utilisateur actuellement connecté comme createur de l'article
            $article->setUser($this->getUser());

            // Définition la date de publication comme la date actuelle
            $article->setDatePublication(new \DateTime());

            $articleRepository->save($article, true);
            $this->addFlash('notice', 'Votre article a été enregistrée');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/add-comment', name: 'article_add_comment')]
    public function addComment(Request $request, Article $article, CommentaireRepository $commentaireRepository)
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setArticle($article);
            $commentaire->setUser($this->getUser());
            $commentaire->setDateCommentaire(new \DateTime());
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('article_add_comment', ['id' => $article->getId()]);
        }
        $commentaires = $commentaireRepository->findBy(['article' => $article]);
        return $this->render('home/add_comment.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'commentaires' => $commentaires,
        ]);
    }
}
