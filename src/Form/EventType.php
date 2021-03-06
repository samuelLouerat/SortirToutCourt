<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Category;
use App\Entity\Place;
use App\Entity\User;
use App\Entity\Event;
use DateInterval;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startTime',DateTimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('registrationTimeLimit',DateTimeType::class, [
                'widget' => 'single_text'])
            ->add('registrationMax')
            ->add('duration',DateIntervalType::class,[
            'widget'      => 'choice',
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'with_hours'  => true,
                'with_minutes'  => true,
])
            ->add('eventInfo',TextareaType::class)
            ->add('campusSite',EntityType::class,
                ['class'=>Campus::class,  'choice_label'=>'name'])
            ->add('place',EntityType::class, ['class'=>Place::class, 'choice_label'=>'name','choice_value'=>'id']);
            //->add('place',PlaceType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
