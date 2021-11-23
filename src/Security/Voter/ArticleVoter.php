<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ArticleVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    private $security;
    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Article) {
            return false;
        }

        return true;

    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Post $post */
        $article = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canEdit($article, $user);
            case self::VIEW:
                
                // logic to determine if the user can VIEW
                // return true or false
                return $this->canView($article, $user);
            
        }
        return false;
    }

    private function canEdit(Article $article, User $user):bool{
        return $user === $article->getUser();
    }
    
    private function canView(Article $article, User $user):bool{

        if ($this->canEdit($article, $user)) {
            return true;
        }
        if($article->getEtatVente() == Article::DEMARREE || $article->getEtatVente() == Article::TERMINEE){
            return true;
        }
        return false;
    }
}
