<?php

namespace App\Controller\Admin;

use App\Entity\Pickup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PickupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pickup::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
