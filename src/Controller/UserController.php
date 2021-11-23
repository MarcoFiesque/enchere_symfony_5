<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/{id}/details', name: 'user.details')]
    public function details(UserRepository $repository, int $id): Response
    {
        $user = $repository->find($id);
        $articles = $this->em->getRepository(Article::class);
        // $address = $user->getAddress();
        // dd($user->getAddress());
        
        return $this->render('user/details.html.twig', [
            'user'=>$user,
            'articles'=>$articles,
            'address'=>$user->getAddress(),
        ]);
    }
}
