<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('dateDebutEnchere', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'required' => false
            ])
            ->add('dateFinEnchere', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'required' => false
            ])
            ->add('etatVente', ChoiceType::class,[
                'choices' => Article::ETATS_VENTE,
                'empty_data' => Article::ATTENTE
            ])
            ->add('miseAPrix', NumberType::class, [
                'required'=>false,
            ])
            ->add('prixVente')
            ->add('category', EntityType::class,[
                'class'=>Category::class,
                'choice_label'=>'name'
            ])
            // ->add('pickup', EntityType::class,[
            //     'class'=>Pickup::class,
            //     'choice_label'=>'name'
            // ])
            ->add('pickup', PickupType::class, [
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'translation_domain' => 'forms'
        ]);
    }
}
