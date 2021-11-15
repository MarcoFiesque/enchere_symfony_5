<?php

namespace App\Form;

use App\Entity\Address;
use PhpParser\Parser\Multiple;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\CallbackTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(new CallbackTransformer(
                function (Collection $value):array{
                    return $value->map(fn($d)=>(string)$d->getId())->toArray();
                },
                function (array $ids) use ($options) : Collection{
                    if(empty($ids))
                    {
                        return new ArrayCollection([]);
                    }
                    return new ArrayCollection(
                        $this->em->getRepository($options['class'])->findBy(['id'=>$ids])
                    );
                }
            ))
            ->add('street')
            ->add('postalCode')
            ->add('city')
            // ->add('users')
        ;
    }
    public function getBlockPrefix()
    {
        return 'choices';
    }

    public function choices(Collection $value)
    {
        return $value
            ->map(fn($d)=>new ChoiceView($d, (string)$d->getId(), (string)$d))
            ->toArray();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => true,
            'data_class' => Address::class,
        ]);
    }
}
