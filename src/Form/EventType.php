<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startTime')
            ->add('duration')
            ->add('registrationTimeLimit')
            ->add('registrationMax')
            ->add('eventInfo')
            ->add('organizer', EntityType::class, ['class'=>User::class, 'choice_label'=>'pseudo'])
            ->add('users',EntityType::class, ['class'=>User::class, 'choice_label'=>'pseudo'])
            ->add('campusSite',EntityType::class, ['class'=>Campus::class, 'choice_label'=>'nom'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
