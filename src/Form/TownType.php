<?php

namespace App\Form;

use App\Entity\Town;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TownType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,["label"=>'ville','attr' => ['onkeydown' => "callApi()",'list'=>"datalistOptions"]])
            ->add('postCode', TextType::class,["label"=>'code postal' ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Town::class,
        ]);
    }
}
