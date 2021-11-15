<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function __toString()
    {
        return $this->name;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }*/
    public function configureFields(string $pageName): iterable
    {
        return [
            'name',
            'description',
            'dateDebutEnchere',
            'dateFinEnchere',
            ChoiceField::new('etatVente')->setChoices(Article::ETATS_VENTE),
            'miseAPrix',
            'prixVente',
            AssociationField::new('bids'),
            AssociationField::new('category'),
            AssociationField::new('pickup')

        ];
    }
    
}
