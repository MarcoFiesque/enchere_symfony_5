<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    private $em;
    private $repository;
    public function __construct(ArticleRepository $repository,  EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    #[Route('/article/list', name: 'article.list')]
    public function index(): Response
    {
        // $articles = $this->em->getRepository(Article::class)->findAl;
        // $categories = $this->em->getRepository(Category::class)->findAll();
        $articles = $this->repository->findAll();
        $categories = $this->em->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles'=>$articles,
            'categories'=>$categories,
            'active_menu'=>'article'
        ]);
    }

    #[Route('/article/{id}/edit', name:"article.edit")]
    public function edit(int $id, Request $request): Response
    {
        $article = $this->repository->find($id);

        if ($article->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        // dd($article);
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', 'Article modifié !');

            return $this->redirectToRoute('article.details', [
                'id' => $article->getId(),
            ]);
        }
        return $this->render('article/articleForm.html.twig', [
            'articleForm'=>$form->createView(),
            'article'=>$article,
        ]);
    }

    #[Route('/article/{id}/details', name: 'article.details')]
    public function details(int $id): Response
    {
        $article = $this->repository->find($id);
        $leading = null;
        $bids = $article->getBids();
        $bidsArray = $bids->toArray();
        if(count($bidsArray)>0){
            $leading = max($bidsArray);
        }
        return $this->render('article/details.html.twig', ["article"=>$article, "leading"=>$leading]);
    }

    #[Route('/article/create', name:"article.create")]
    public function create(Request $request): Response{
        $article = new Article();
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid() ) {
            $article->setUser($user);

            $article->setDateDebutEnchere(new DateTime());    
            if($article->getDateFinEnchere() == null){
                $article->setDateFinEnchere(new DateTime());
                $article->setDateFinEnchere(date_add($article->getDateFinEnchere(), new DateInterval("P1D")) );
            } 
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', 'Article créé !');

            return $this->redirectToRoute('article.details', [
                'id' => $article->getId(),
            ]);
        }
        return $this->renderForm('article/articleForm.html.twig', [
            'articleForm'=>$form,
            'article'=>$article,
        ]);
    }

    #[Route('/article/{id}/delete', name:'article.delete', methods: ['DELETE'])]
    public function remove(int $id, Request $request)
    {
        $article = $this->repository->find($id);
        if ($article->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        if($this->isCsrfTokenValid('DELETE' . $article->getId(), $request->request->get('_token')))
        {
            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash('success', 'Article supprimé !');

            return $this->redirectToRoute('article.list');
        }
 
    }
}
