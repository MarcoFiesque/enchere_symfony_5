<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
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
        // $address = $user->getAddress();
        // dd($user->getAddress());
        return $this->render('user/details.html.twig', [
            'user'=>$user,
            'address'=>$user->getAddress(),
        ]);
    }
}
