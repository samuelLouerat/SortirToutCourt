<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Place;
use App\Entity\User;
use App\Entity\Event;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class EventCancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startTime')
            ->add('registrationTimeLimit',DateType::class,[
                'attr' => ['hidden' => 'true'],
            ] )
            ->add('registrationMax',HiddenType::class, array(
        'required' => true,
    ))
            ->add('duration' )
            ->add('eventInfo',TextareaType::class,[
                'attr' => ['placeholder' => 'Motif'],
            ])
            ->add('campusSite',EntityType::class,
                ['class'=>Campus::class, 'choice_label'=>'name'])

     ;
            //->add('place',EntityType::class, ['class'=>Place::class, 'choice_label'=>'name','by_reference' => false,'multiple'=>false])







    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
