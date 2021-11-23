<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BidController extends AbstractController
{
    // #[Route('/bid', name: 'bid')]
    // public function index(): Response
    // {
    //     return $this->render('bid/index.html.twig', [
    //         'controller_name' => 'BidController',
    //     ]);
    // }

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/bid/registration/{articleId}', name: 'bid_registration', methods: 'POST')]
    public function newBid(Request $request, int $articleId, Security $security): Response
    {
        $token = $request->request->get('token');
        $article = $this->em->getRepository(Article::class)->find($articleId);
        
        $referer = $request->headers->get('referer'); // get the referer, it can be empty!
            if (!\is_string($referer) || !$referer) {
                echo 'Referer is invalid or empty.';

                Throw new Exception("Referer empty");
            }

            $refererPathInfo = Request::create($referer)->getPathInfo();

            // try to match the path with the application routing
            $routeInfos = $this->get('router')->match($refererPathInfo);

            // get the Symfony route name if it exists
            $refererRoute = $routeInfos['_route'] ?? '';

        if ($this->isCsrfTokenValid('register-bid', $token) && $article->getEtatVente()) {
            $seller = $security->getUser();
            $bidValue = (int)$request->request->get('bid_value');
            if($bidValue > 0){

                $bid = new Bid();
                $bid->setArticle($article);
                $bid->setUser($seller);
                $bid->setBidAmmount($bidValue);
            }

            if($article->getMaxBidValue() < $bidValue){
                $this->em->persist($bid);
                $this->em->flush();
                
                $article->addBid($bid);
                $article->setMaxBidValue($bidValue);
                $bids = $article->getBids();
                $this->em->persist($article);
                $this->em->flush();
                
                $this->addFlash('success', 'Enchère émise');
                return $this->redirectToRoute($refererRoute,['id'=>$article->getId()]);
            }
        }
            $this->addFlash('warning', "Vous devez proposer plus de {$article->getMaxBidValue()} points !");
            return $this->redirectToRoute($refererRoute,['id'=>$article->getId()]);
            
    }
}
