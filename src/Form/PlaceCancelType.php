<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceCancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['disabled'=>'true'])
            ->add('street',TextType::class,['disabled'=>'true'])
            ->add('town',EntityType::class, ['class'=>Town::class, 'choice_label'=>'name','disabled'=>'true'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
