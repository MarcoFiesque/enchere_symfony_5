<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Bid;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Repository\BidRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    private $em;
    private $repository;
    private $bidRepository;
    public function __construct(ArticleRepository $repository, BidRepository $bidRepository, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->bidRepository = $bidRepository;
    }

    // #[Route('/article/list', name: 'article.list')]
    // public function index(): Response
    // {
    //     // $articles = $this->em->getRepository(Article::class)->findAl;
    //     // $categories = $this->em->getRepository(Category::class)->findAll();
    //     $articles = $this->repository->findAll();
    //     $categories = $this->em->getRepository(Article::class)->findAll();
    //     return $this->render('article/index.html.twig', [
    //         'articles'=>$articles,
    //         'categories'=>$categories,
    //         'active_menu'=>'article'
    //     ]);
    // }
    #[Route('/article/list', name: 'article.list')]
    public function list(): Response
    {
        $articles = $this->repository->findAllVisible();
        // $categories = $this->em->getRepository(Category::class)->findAll();

        $categories = $this->em->getRepository(Category::class)->findAll();

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
        
        $this->denyAccessUnlessGranted('edit', $article);

        if (!$article) {
            throw $this->createNotFoundException(
                'Aucun article pour l\'id: ' . $id
            );
        }

        if ($article->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

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
            'mode'=>'edit'
        ]);
    }

    #[Route('/article/{id}/details', name: 'article.details')]
    public function details(int $id): Response
    {
        $article = $this->repository->find($id);
        $now = new \DateTime();
        $timeRemaining = date_diff($now, $article->getDateFinEnchere());
        if($timeRemaining > 0){
            $article->setEtatVente(Article::TERMINEE);
        }
        $bids = $this->bidRepository->findAllByMaxBidAmmount();
        $maxBid = $bids[0];
        $this->denyAccessUnlessGranted('view', $article);
        
        return $this->render('article/details.html.twig', ["article"=>$article, "maxBid"=>$maxBid, "timeRemaining"=>$timeRemaining]);
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
            'mode'=>'create'
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
