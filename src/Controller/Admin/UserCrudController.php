<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Article;
use App\Form\AddressType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function __toString()
    {
        return $this->firstname. ' ' .$this->lastname;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            'pseudo',
            'firstname',
            'lastname',
            EmailField::new('email'),
            AssociationField::new('address')
            ->autocomplete()
            ->setFormType(AddressType::class)
            ->setFormTypeOptions([
                'by_reference' => true,
                'multiple' => true,
                // 'allow_add' => true,
                'class' => Address::class
            ]),
            'phone',
            'administrator',

        ];
    }
}
